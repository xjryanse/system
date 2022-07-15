<?php
namespace xjryanse\system\logic;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

use xjryanse\logic\DbOperate;
use xjryanse\logic\Arrays;
use xjryanse\logic\Arrays2d;
use xjryanse\system\service\SystemFileService;
use xjryanse\logic\SnowFlake;
use xjryanse\logic\Debug;
use Exception;
use think\Db;
/**
 * 导入逻辑
 */
class ImportLogic
{
    use \xjryanse\traits\DebugTrait;
    /**
     * 数据导入逻辑
     * @param string $file  文件路径
     * @param int $sheet    单元格
     * @param int $columnCnt    起始列
     * @param int $_row         起始行
     * @param type $options     替换选项
     * @return type
     * @throws Exception
     */
    public static function importExecl(string $file = '', int $sheet = 0, int $columnCnt = 0, $row = 1, &$options = [])
    {
        /* 转码 */
        $file = iconv("utf-8", "gb2312", $file);

        if (empty($file) OR !file_exists($file)) {
            throw new \Exception('文件不存在!');
        }

        /** @var Xlsx $objRead */
        $objRead = IOFactory::createReader('Xlsx');

        if (!$objRead->canRead($file)) {
            /** @var Xls $objRead */
            $objRead = IOFactory::createReader('Xls');

            if (!$objRead->canRead($file)) {
                throw new \Exception('只支持导入Excel文件！');
            }
        }

        /* 如果不需要获取特殊操作，则只读内容，可以大幅度提升读取Excel效率 */
        empty($options) && $objRead->setReadDataOnly(true);
        /* 建立excel对象 */
        $obj = $objRead->load($file);
        /* 获取指定的sheet表 */
        $currSheet = $obj->getSheet($sheet);

        if (isset($options['mergeCells'])) {
            /* 读取合并行列 */
            $options['mergeCells'] = $currSheet->getMergeCells();
        }

        if (0 == $columnCnt) {
            /* 取得最大的列号 */
            $columnH = $currSheet->getHighestColumn();
            Debug::debug( '$columnH', $columnH );
            /* 兼容原逻辑，循环时使用的是小于等于 */
            $columnCnt = Coordinate::columnIndexFromString( $columnH );
        }

        /* 获取总行数 */
        $rowCnt = $currSheet->getHighestRow();
        $data   = [];

        /* 读取内容 */
        for ($_row = $row; $_row <= $rowCnt; $_row++) {
            $isNull = true;

            for ($_column = 1; $_column <= $columnCnt; $_column++) {
                $cellName = Coordinate::stringFromColumnIndex($_column);
                $cellId   = $cellName . $_row;
                $cell     = $currSheet->getCell($cellId);

                if (isset($options['format'])) {
                    /* 获取格式 */
                    $format = $cell->getStyle()->getNumberFormat()->getFormatCode();
                    /* 记录格式 */
                    $options['format'][$_row][$cellName] = $format;
                }

                if (isset($options['formula'])) {
                    /* 获取公式，公式均为=号开头数据 */
                    $formula = $currSheet->getCell($cellId)->getValue();

                    if (0 === strpos($formula, '=')) {
                        $options['formula'][$cellName . $_row] = $formula;
                    }
                }

                if (isset($format) && 'm/d/yyyy' == $format) {
                    /* 日期格式翻转处理 */
                    $cell->getStyle()->getNumberFormat()->setFormatCode('yyyy/mm/dd');
                }
                $tempValue = trim($currSheet->getCell($cellId)->getFormattedValue());

                //首行为空的，后面则不取
                if($_row == 1 && empty($tempValue)){
                    $columnCnt = $_column - 1 ;
                    break;
                }
                
                $data[$_row][$cellName] = $tempValue;

                if (!empty($data[$_row][$cellName])) {
                    $isNull = false;
                }
            }

            /* 判断是否整行数据为空，是的话删除该行数据，并且返回结果 */
            if ($isNull) {
                unset($data[$_row]);
                return $data;
            }
        }

        return $data;
    }
    /**
     * 从excel中，根据单元格提取值
     * @param string $file
     * @param int $sheet
     * @param int $columnCnt
     * @param type $options
     * @return type
     * @throws Exception
     */
    public static function excelGetByFields(string $file = '',$sheet = 0,&$options = [])
    {
        /* 转码 */
        $file = iconv("utf-8", "gb2312", $file);

        if (empty($file) OR !file_exists($file)) {
            throw new \Exception('文件不存在!');
        }

        /** @var Xlsx $objRead */
        $objRead = IOFactory::createReader('Xlsx');

        if (!$objRead->canRead($file)) {
            /** @var Xls $objRead */
            $objRead = IOFactory::createReader('Xls');

            if (!$objRead->canRead($file)) {
                throw new \Exception('只支持导入Excel文件！');
            }
        }

        /* 如果不需要获取特殊操作，则只读内容，可以大幅度提升读取Excel效率 */
        empty($options) && $objRead->setReadDataOnly(true);
        /* 建立excel对象 */
        $obj = $objRead->load($file);
        /* 获取指定的sheet表 */
        $currSheet = $obj->getSheet($sheet);
        //拼接数组
        $data = [];
        foreach($options as $k=>$value){
            // $k A2 B2 C2 D2
            $data[$value] = $currSheet->getCell($k)->getValue();
        }
        return $data;
    }

    /**
     * 快速数据导入逻辑
     * @param string $file
     * @param int $sheet
     * @param int $columnCnt
     * @param type $options
     * @return type
     * @throws \Exception
     */
    public static function importExeclFast(string $file = '', int $sheet = 0, int $columnCnt = 0,$row=1, &$options = [])
    {
        /* 转码 */
        $file = iconv("utf-8", "gb2312", $file);

        if (empty($file) OR ! file_exists($file)) {
            throw new \Exception('文件不存在!');
        }

        /** @var Xlsx $objRead */
        $objRead = IOFactory::createReader('Xlsx');

        if (!$objRead->canRead($file)) {
            /** @var Xls $objRead */
            $objRead = IOFactory::createReader('Xls');

            if (!$objRead->canRead($file)) {
                throw new \Exception('只支持导入Excel文件！');
            }
        }

        /* 如果不需要获取特殊操作，则只读内容，可以大幅度提升读取Excel效率 */
        empty($options) && $objRead->setReadDataOnly(true);
        /* 建立excel对象 */
        $objPHPExcel = $objRead->load($file);

        $sheet_count = $objPHPExcel->getSheetCount();
        $dataArr = [];
        //多表全读
//        for ($s = 0; $s < $sheet_count; $s++) {
            $s = 0; //TODO
            $currentSheet = $objPHPExcel->getSheet($s); // 当前页 
            $row_num = $currentSheet->getHighestRow(); // 当前页行数 
            $col_max = $currentSheet->getHighestColumn(); // 当前页最大列号 
            // 循环从第二行开始，第一行往往是表头 
            for ($i = $row; $i <= $row_num; $i++) {
                $cell_values = array();
                for ($j = 'A'; $j <= $col_max; $j++) {
                    $address = $j . $i; // 单元格坐标 
                    $cell_values[] = $currentSheet->getCell($address)->getFormattedValue();
                }
                $dataArr[] = $cell_values;
            }
//        }
        return $dataArr;
    }

    /**
     * excel文件取二维数组数据
     */
    /**
     * 
     * @param type $fileId
     * @param type $arrayCov
     * @param type $maxLimit    最大可导条数
     * @return boolean
     */
    public static function fileGetArray( $fileId, $arrayCov ,$maxLimit = 0)
    {
        //临时
        if(isset($fileId['id']) ){
            $fileId = $fileId['id'];
        }
        self::debug('$infoSqlId',$fileId);
        $info   = SystemFileService::mainModel()->field('*,file_path as rawPath,file_size')->get( $fileId );
        self::debug('$info',$info);
        if($info["file_size"] > 3145728){
            SystemFileService::getInstance( $fileId )->unlink();
            throw new Exception('文件超过3M无法解析，请分开上传');
        }
        
        $path   = $info['rawPath'] ;
        //访问路径
        self::debug('$path',$path);
        
        if(!file_exists( $path ) && file_exists( mb_substr($path,1 ))){
            //去除首字符反斜杠
            $path   = mb_substr($path,1 );
        }
        if(!$path){
            return false;
        }

//        $data       = self::importExecl( $path ); //逐步弃用：20210520
        $data       = self::importExeclFast( $path );
        if($maxLimit && count($data) > $maxLimit){
            throw new Exception('最多可导入'.$maxLimit.'条(当前'. count($data) .'条)');
        }

        $shiftToKey = Arrays2d::shiftToKey( $data );
        $resData    = Arrays2d::keyReplace( $shiftToKey, $arrayCov );

        foreach( $resData as &$value ){
            //形如$data["prizeInfo.sellerTmAuthDeposit"] = 'aaa';的数据，
            //转为$data['prizeInfo']['sellerTmAuthDeposit'] = 'aaa';
            $value = Arrays::keySplit($value);
        }
        return $resData;
    }
            //形如$data["prizeInfo.sellerTmAuthDeposit"] = 'aaa';的数据，
            //转为$data['prizeInfo']['sellerTmAuthDeposit'] = 'aaa';
    /**
     * 导入
     * @param type $tableName       导入表名
     * @param type $fileId          文件id
     * @param array $headers        表头转换
     * @param array $preInputData   预录数据
     */
    public static function doImport( $tableName, $fileId, array $headers, array $preInputData, $covData=[] )
    {
//        $service    = DbOperate::getService( $tableName );
        self::debug('$fileId',$fileId);
        $resData    = self::fileGetArray( $fileId ,$headers );

        self::debug('$resData',$resData);
        if(!$resData){
            return false;
        }
        foreach($resData as &$v){
            $v          = array_merge( $preInputData , $v );
            $v['id']    = SnowFlake::generateParticle();
            //用于拆分
            $v['val']   = json_encode($v,JSON_UNESCAPED_UNICODE);     
        }
        $importSql = DbOperate::saveAllSql($tableName, $resData,$covData);

        //返回受影响行数
        return Db::execute($importSql);
    }
    /**
     * 导入数据转换
     */
    /**
     * 
     * @param type $data
     * @param type $covData     {"holder_type":{"公司":"customer","个人":"personal"},"is_buyer":{"否":0,"是":1}}
     */
    public static function importDataCov($data,$covData)
    {
        foreach( $data as &$value){
            foreach($value as $kk=>&$vv){
                $vv = (isset($covData[$kk]) && $covData[$kk][$vv]) ?  $covData[$kk][$vv] : $vv;
            }
        }
        return $data;
    }
    

}
