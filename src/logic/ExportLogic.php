<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemExportLogService;

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
        $con[] = ['status','=','0'];
        $lists = SystemExportLogService::lists( $con );
        //导出中
        SystemExportLogService::mainModel()->where( $con )->update(['status'=>1]);
        
        foreach($lists as &$v){
            $dataTitle  =json_decode($v['filed_json']) ? :[]; 
            $sql        = $v['search_sql'];
            $fileName   = $v['file_name'] 
                    ? $v['file_name'].'.csv' 
                    : date('Ymdhis').'-'.$v['id'].'.csv';
            //【核心】导出逻辑
            $this->exportToCsv( $sql ,$dataTitle, $fileName);

            $updata['file_name']      = $fileName;
            $updata['file_path']      = '/Uploads/Download/CanDelete/'.$fileName;
            $updata['status']         = 2;
            $updata['finish_time']    = date('Y-m-d H:i:s');
            //导出后结果更新
            SystemExportLogService::mainModel()->where('id',$v['id'])->where('status',1)->update( $updata );
            usleep( 500 );
        }        
    }

    /**
     * 低内存异步导出csv
     * @param string $sql       导出的查询sql语句
     * @param array $dataTitle  数据标题
     */
    public function exportToCsv( string $sql,array $dataTitle = [],string $filename='' )
    {
        //TODO，目前只有TP框架可用
        $dbInfo     = config('database.');
        //新建PDO连接
        $connectStr = 'mysql:host='.$dbInfo['hostname'].';port='.$dbInfo['hostport'].';dbname='.$dbInfo['database'];
        
        $pdo        = new \PDO( $connectStr , $dbInfo['username'], $dbInfo['password']);
        $pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        //防中文乱码
//        $pdo->query("set names 'utf8'");
        $pdo->query("set names 'ANSI'");

        $rows = $pdo->query($sql);
        //写入csv
        return $this->putIntoCsv( $rows ? : [] ,$dataTitle,$filename );
    }
    /**
     * 传入csv
     * @param type $rows
     * @param array $dataTitle
     * @param string $filename
     * @return string
     */
    private function putIntoCsv( $rows ,array $dataTitle = [],string $filename=''  )
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
        $keys = [];

        foreach ($rows as $row) {
            if(!$keys){
                $keys = $this->getPdoKeys($row);
            }
            if(!$dataTitle){
                $dataTitle = $keys;
                fputcsv($out, $this->utf8ToGbk($dataTitle));
            }

            $line = [];
            foreach( $keys as &$v ){
                $line[] = $row[$v];
            }
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
     * 自动删除超过30天的文件
     */
    public function deleteExpire()
    {
        $con[] = ['status','<>','0'];
        $con[] = ['status','<>','5'];
        $con[] = ['finish_time','<=',date("Y-m-d",strtotime("-30 day"))];  //30天前的数据
        
        $lists = SystemExportLogService::mainModel()->where( $con )->select();
        foreach( $lists as $v){
            if(file_exists( $this->getBasePath() .'/Download/CanDelete/'.$v['file_name'] )){
                unlink( $this->getBasePath() .'/Download/CanDelete/'.$v['file_name'] );            
            }
            SystemExportLogService::mainModel()->where( 'id',$v['id'] )->update(['status'=>4]);
        }
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
        foreach ($data as $k => $v) {
            $data[$k] = iconv("UTF-8", "GB2312//IGNORE", $v);  // 这里将UTF-8转为GB2312编码
        }
        return $data;
    }    
    

}
