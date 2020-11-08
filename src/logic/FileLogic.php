<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemFileService;
use xjryanse\logic\Url;
use think\File as tpFile;
/**
 * 文件处理逻辑
 */
class FileLogic
{
    /**
     * 保存远程文件到本地服务器
     * @param type $url         资源路径
     * @param type $type        类型：默认images
     * @param type $savePath    本地服务器保存路径
     */
    public static function saveUrlFile( $url , $type="images", $savePath="" )
    {
        //读取文件
        $file   = file_get_contents( $url );
        //读取后缀
        $ext    = Url::getExt( $url );
        //生成保存路径
        if(!$savePath){
            $savePath = "./".$type."/".date('Ymd')."/".uniqid().$ext;
        }
        //写入本地服务器
        file_put_contents( $savePath, $file );
        
        $tpFile = new tpFile( $savePath );
        //文件信息存数据库
        return SystemFileService::uplSave( $tpFile, $savePath );
    }

}
