<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemFileService;
use xjryanse\system\service\SystemErrorLogService;
use xjryanse\logic\Url;
use xjryanse\logic\Folder;
use think\File as tpFile;
use Exception;
/**
 * 文件处理逻辑
 */
class FileLogic
{
    /**
     * 保存远程文件到本地服务器
     * @param type $url         资源路径
     * @param type $type        类型：默认images
     * @param type $defaultExt
     * @param type $savePath    本地服务器保存路径
     * @return type
     * @throws Exception
     */
    public static function saveUrlFile( $url , $type="image", $defaultExt="jpg", $savePath="" )
    {
        //读取文件
        try{
            $file   = file_get_contents( $url );
            //读取后缀
            $ext    = Url::getExt( $url ) ? : $defaultExt ;
            //生成保存路径
            if(!$savePath){
                //文件名
                $dirname    = "./".$type."/".date('Ymd');
                if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
                    throw new Exception('创建目录'. $dirname .'失败');
                }
                //保存路径
                $savePath   = $dirname ."/".uniqid();
                //后缀
                if( $ext ){
                    $savePath .= '.'.$ext;
                }
            }
            //写入本地服务器
            file_put_contents( $savePath, $file );
        } catch (\Exception $e){ 
            SystemErrorLogService::exceptionLog($e);
            return [];
        }
        
        $tpFile = new tpFile( $savePath );

        $data['file_type'] = $type;
        //文件信息存数据库;移除点
        return SystemFileService::uplSave( $tpFile, ltrim( $savePath , './'), $data );
    }
    /**
     * 判断文件是否存在
     * @param type $idOrPath    id或路径
     */
    public static function fileExists( $idOrPath )
    {
        if(is_numeric($idOrPath)){
            $pathRaw = SystemFileService::getInstance($idOrPath)->fFilePath();
            if(!$pathRaw){ return false;}
            $path = '.'.$pathRaw;
        } else {
            $path = $idOrPath;
        }
        return file_exists($path);
    }
    
    public static function savePathFiles( $path )
    {
        $files = Folder::getFiles($path);
        $ids = [];
        foreach( $files as $v){
            $dir = $path . $v;
            $ids[$v] = SystemFileService::pathSaveGetId( $dir );
        }
        return $ids;
    }
}
