<?php
namespace xjryanse\system\model;

/**
 * 公司端口
 */
class SystemCompany extends Base
{
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