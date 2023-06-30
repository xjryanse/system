<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemFileService;
use xjryanse\system\service\SystemErrorLogService;
use xjryanse\logic\Url;
use xjryanse\logic\Debug;
use xjryanse\logic\Folder;
use think\File as tpFile;
use xjryanse\logic\Oss;
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
        //20220903:增加判断修复报错
        if(!$url){
            return [];
        }
        //生成保存路径
        if(!$savePath){
            //读取后缀
            $ext    = Url::getExt( $url ) ? : $defaultExt ;
            //文件名
            $baseDir    = $type."/".date('Ymd');
            $dirname    = './'.$baseDir;
            if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
                throw new Exception('创建目录'. $dirname .'失败');
            }
            //保存路径
            $fileName = $baseDir."/".uniqid();
            //后缀
            if( $ext ){
                $fileName .= '.'.$ext;
            }
            $savePath = './'.$fileName;
        }
        //读取文件
        try{
            $file   = file_get_contents( $url );
            //写入本地服务器
            file_put_contents( $savePath, $file );
            //$res = Oss::getInstance()->uploadFile($fileName,$savePath);
        } catch (\Exception $e){ 
            SystemErrorLogService::exceptionLog($e);
            return [];
        }
        Debug::debug('进入保存状态$savePath',$savePath);
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
