<?php
namespace xjryanse\system\model;

/**
 * 上传附件
 */
class SystemFile extends Base
{
    /**
     * 文件路径
     * @param type $value
     * @return type
     */
    public function getFilePathAttr( $value )
    {
        if(session('viewSource') == 'user'){
            return self::cdnUrlEncrypt( $value);
        } else {
            $baseUrl = config('xiesemi.systemBaseUrl');
            return $value ? $baseUrl .'/'. $value : $value;
        }
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