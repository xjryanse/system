<?php
namespace xjryanse\system\model;

use xjryanse\logic\Oss;
use xjryanse\logic\Debug;
use xjryanse\logic\Arrays;
use think\facade\Request;
/**
 * 上传附件
 */
class SystemFile extends Base
{
//    /**
//     * 文件路径
//     * @param type $value
//     * @return type
//     */
//    public function getFilePathAttr( $value )
//    {
//        if(!$value){
//            return '';
//        }
////        if(session('viewSource') == 'user'){
////            return self::cdnUrlEncrypt( $value);
////        }
//        $basePath       = Arrays::value($_SERVER, 'DOCUMENT_ROOT');
//        $filePathFull   = $basePath .'/'. $value;
//        if(file_exists( $filePathFull )){
//            return $value ? Request::domain(true) .'/'. $value : $value;
//        }
//        // 2022-12-11：增加OSS的路径返回
//        $ossPath = Oss::getInstance()->signUrl($value);
//        return $ossPath;
//    }
    /**
     * 20230516:获取完整上传url
     */
    public function getFullPath($path){
        $value = $path;
        if(!$value){
            return '';
        }
//        if(session('viewSource') == 'user'){
//            return self::cdnUrlEncrypt( $value);
//        }
        $basePath       = Arrays::value($_SERVER, 'DOCUMENT_ROOT');
        $filePathFull   = $basePath .'/'. $value;
        if(file_exists( $filePathFull )){
            $port = Arrays::value($_SERVER, 'SERVER_PORT');
            $urlBase = $port && !in_array($port,[80,443])
                    ? Request::domain(true).':'.$port 
                    : Request::domain(true);
            
            return $value ? $urlBase .'/'. $value : $value;
        }
        // 2022-12-11：增加OSS的路径返回
        $ossPath = Oss::getInstance()->signUrl($value);
        return $ossPath;        
    }
    
    /*
     * CNDurl加密
     */
    protected static function cdnUrlEncrypt( $url )
    {
//        $time = time();
//        $urlKeyBase = $time .'-'.self::newId().'-0-';
//        $urlKey = $url . '-' . $urlKeyBase.config('xiesemi.cdnEncryptKey');
//        $urlRes = config('xiesemi.systemCdnUrl').$url.'?auth_key='.$urlKeyBase.md5($urlKey);
        $urlRes = config('xiesemi.systemCdnUrl').$url;
        return $urlRes;
    }
}