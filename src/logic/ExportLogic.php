<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemExportLogService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use xjryanse\logic\DbOperate;
use xjryanse\logic\Debug;
use xjryanse\logic\Strings;
use think\Db;
/**
 * 数据导出逻辑
 */
class ExportLogic
{
    use \xjryanse\traits\InstTrait;
    
    private $BasePath;
    
    /**
     * 获取根目录
     */
    public function getBasePath()
    {
        if(!$this->BasePath){
            $this->BasePath = 'Uploads';
        }
        return $this->BasePath;
    }

    /**
     * 导csv
     */
    public function export()
    {
        $con[]      = ['exp_status','=','0'];
        $lists      = SystemExportLogService::mainModel()->where( $con )->select();
        $listsArr   = $lists ? $lists->toArray() : [];
        if(!$listsArr){
            return false;
        }
        $cond[]     = ['id','in',array_column($listsArr,'id')];
        //导出中
        SystemExportLogService::mainModel()->where( $cond )->update(['exp_status'=>1]);
        foreach($lists as &$v){
            $dataTitle  =json_decode($v['filed_json'],true) ? :[]; 
            $sql        = $v['search_sql'];
            $fileName   = $v['file_name'] 
                    ? $v['file_name'].'.xlsx' 
                    : date('Ymdhis').'-'.$v['id'].'.xlsx';
            Debug::debug('$fileName',$fileName);
            $filePath = '/Uploads/Download/CanDelete/'.$fileName;
            //【核心】导出逻辑
            $res = $this->exportToExcel( $sql ,$dataTitle, '.'.$filePath);
            Debug::debug('导入完成后',$res);
            $updata['file_name']      = $fileName;
            $updata['file_path']      = $filePath;
            $updata['exp_status']         = 2;
            $updata['finish_time']    = date('Y-m-d H:i:s');
            //导出后结果更新
            SystemExportLogService::mainModel()->where('id',$v['id'])->where('status',1)->update( $updata );
            usleep( 500 );
        }
    }
    /*
     * 导出excel
     * @param string $sql       sql语句
     * @param array $dataTitle  标题
     * @param string $savePath  保存路径
     * @param type $config      其他配置
     * @return type
     */
    public static function exportToExcel( string $sql,array $dataTitle = [],string $savePath='',$rowStart=2 ){
        //20211015 主库性能较好
        $data = Db::query($sql,[],true);
        return self::dataExportExcel($data, $dataTitle,$savePath,$rowStart);
    }
    
    public static function dataExportExcel(array $data, array $dataTitle = [], $savePath='',$rowStart=2){
        $dataImport[] = $dataTitle;
        foreach($data as $v){
            $dataImport[] = array_intersect_key($v, $dataTitle);
        }
        $tplPath = "";
        return self::writeToExcel($dataImport, $rowStart, $tplPath, $savePath);
    }
    
    /**
     * 低内存异步导出csv
     * @param string $sql       导出的查询sql语句
     * @param array $dataTitle  数据标题
     */
    public function exportToCsv( string $sql,array $dataTitle = [],string $filename='' )
    {
        //pdo高效率查询
        $rows = DbOperate::pdoQuery($sql);
        //写入csv
        return $this->putIntoCsv( $rows ? : [] ,$dataTitle,$filename );
    }
    /**
     * 传入csv
     * @param type $rows
     * @param array $dataTitle  数组；和keys对应
     * @param string $filename
     * @param type $keys        数组，和dataTitle对应
     * @return string
     */
    public function putIntoCsv( $rows ,array $dataTitle = [],string $filename='' ,$keys = [] )
    {        
        if(!$filename){
            $filename = date('YmdHis') .microtime(). '.csv'; //设置文件名        
        }
        
        //文件夹
        $filedir = $this->getBasePath() .'/Download/CanDelete/';
        $out = fopen( $filedir . $filename , 'w');
//        fwrite($out,chr(0xEF).chr(0xBB).chr(0xBF));
//        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
        if($dataTitle){
            fputcsv($out, $this->utf8ToGbk($dataTitle));
        }
        foreach ($rows as $row) {
            /*
            if(!$keys){
                $keys = $this->getPdoKeys($row);
            }
             */
            if(!$dataTitle && $keys){
                $dataTitle = $keys;
                fputcsv($out, $this->utf8ToGbk($dataTitle));
            }
            Debug::debug('$keys',$keys);

            if($keys){
                $line = [];
                foreach( $keys as &$v ){
                    $line[] = $row[$v];
                }
            } else {
                $line = $row;
            }
            Debug::debug('$line',$line);
            fputcsv($out, $this->utf8ToGbk($line));
        }
        fclose($out);
        return $filename;        
    }
    /**
     * 数组直接写入csv
     * @param type $rows
     * @param array $dataTitle
     * @param string $filename
     * @param type $needTitle   是否需要标题
     * @return string
     */
    public function arrayIntoCsv( $rows ,array $dataTitle = [],string $filename='',$needTitle = true  )
    {
        if(!$filename){
            $filename = date('YmdHis') .microtime(). '.csv'; //设置文件名        
        }
        
        //文件夹
        $filedir = $this->getBasePath() .'/Download/CanDelete/';
        $out = fopen( $filedir . $filename , 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
        if($dataTitle){
            fputcsv($out, $dataTitle);
        }

        foreach ($rows as $row) {
            if(!$dataTitle && $needTitle){
                $dataTitle = array_keys( $row );
                fputcsv($out, $dataTitle);
            }            
            fputcsv($out, $row);
        }
        fclose($out);
        return $filename;        
    }
    
    /**
     * 写入excel数据表
     * @param type $info        二维数组
     * @param type $rowStart    数据从第几行开始
     * @param type $tplPath     模板路径
     * @param type $savePath    保存路径
     * @param type $replace     数据替换[[0,1,2]]
     * @return string
     */
    public static function writeToExcel(&$info, $rowStart ,$tplPath = '', $savePath = '',$replace=[])
    {
        //扩展
        $ext = strtolower(Strings::getExt($tplPath ? : $savePath));
        if($ext =='xlsx' ){
            $reader     = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        } else {
            $reader     = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
        }
        // 导入模板
        if($tplPath){
            $spreadsheet = $reader->load( $tplPath );
        } else {
            if(file_exists($savePath)){
                $spreadsheet = $reader->load( $savePath );
            } else {
                $spreadsheet     = new Spreadsheet();
            }
        }
        $worksheet = $spreadsheet->getActiveSheet();
        //替换元素
        foreach($replace as &$v){
            //0-列；1-行；2-值；
            $worksheet->setCellValueByColumnAndRow($v[0], $v[1], $v[2]);
        }
        if(is_array($rowStart)){
            foreach ($rowStart as $k=>&$v){
                //数据
                self::writeList($info[$k], $v, $worksheet);
            }
        } else {
            //数据
            self::writeList($info, $rowStart, $worksheet);
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        if(!$savePath){
            $savePath = './Uploads/Download/CanDelete/'.date('YmdHis'). uniqid(). '.xlsx';
        }
        Debug::debug('路径',$savePath);
        $writer->save( $savePath );        
        return $savePath;
    }

    private static function writeList( &$info, $rowStart ,$worksheet)
    {
        //数据
        $row = $rowStart; //从第二行开始
        foreach ($info as &$item) {
            //添加一个新行20220111
            $worksheet->insertNewRowBefore($row, 1);
            //20211219增加，解决模型直接查询数据库后bug
            if(!is_array($item)){
                $item = $item->toArray();
            }
            $column = 1;
            foreach ($item as $k=> &$value) {
                // 20230402:解决长数字，身份证号导出变科学计数法;结尾加上制表符
                $valueCov = is_numeric($value) && mb_strlen($value) > 10 ? $value. "\t" : $value;
                $worksheet->setCellValueByColumnAndRow($column, $row, $valueCov);
                $column++;
            }
            $row++;
        }
    }
    /**
     * 从pdo中获取键名
     */
    private function getPdoKeys( $row)
    {
        $keys = array_keys($row);
        foreach($keys as $k=>&$v){
            if(is_numeric($v)){
                unset($keys[$k]);
            }
        }
        return $keys;
    }
    /**
     * 文件压缩
     */
    public function zip()
    {
        $zip = new \ZipArchive();
        if ($zip->open( $this->getBasePath() .'/Download/CanDelete/test.zip', \ZipArchive::CREATE) === TRUE){
            // 将文件添加到zip文件
            $zip->addFile( $this->getBasePath() .'/Download/CanDelete/20191203085029-11.csv', '20191203085029-11.csv');
            $zip->addFile( $this->getBasePath() .'/Download/CanDelete/测试文件名.csv','测试文件名.csv' );
            // 关闭zip文件
            $zip->close();
        }
    }
    
    public function utf8ToGbk( array $data)
    {
        foreach ($data as $k => &$v) {
            //20220506:E: iconv() expects parameter 3 to be string, array given
            $v = is_string($v) ? iconv("UTF-8", "GB2312//IGNORE", $v) : $v;  // 这里将UTF-8转为GB2312编码
            //处理身份证号码，id等过长的数据会被转为科学计数法
            $v = is_numeric($v) && mb_strlen($v) > 10 ? $v."\t" : $v;  // 这里将UTF-8转为GB2312编码
        }
        return $data;
    }
    

}
