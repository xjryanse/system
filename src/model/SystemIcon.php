<?php
namespace xjryanse\system\model;

/**
 * 图标组
 */
class SystemIcon extends Base
{
    public function setIconImgAttr( $value )
    {
        return self::setImgVal($value);
    }
    public function getIconImgAttr( $value )
    {
        return self::getImgVal($value);
    }  
}