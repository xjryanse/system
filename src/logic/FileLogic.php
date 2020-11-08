<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemFileService;
use xjryanse\logic\Url;
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
    public static function saveUrlFile( $url , $type="images", $defaultExt="jpg", $savePath="" )
    {
        //读取文件
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
        
        $tpFile = new tpFile( $savePath );

        $data['file_type'] = $type;
        //文件信息存数据库;移除点
        return SystemFileService::uplSave( $tpFile, ltrim( $savePath , '.'), $data );
    }

}
