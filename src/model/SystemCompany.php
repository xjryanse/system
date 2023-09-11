<?php
namespace xjryanse\system\model;

/**
 * 公司端口
 */
class SystemCompany extends Base
{
    //20230728 是否将数据缓存到文件
    public static $cacheToFile = true;
    
    public static $picFields = ['logo'];
    
    public function setLogoAttr($value) {
        return self::setImgVal($value);
    }
    public function getLogoAttr($value) {
        return self::getImgVal($value);
    }
    public function setLaunchTimeAttr($value){
        return self::setTimeVal($value);
    }
}