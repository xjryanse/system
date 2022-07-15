<?php
namespace xjryanse\system\model;

/**
 * 图标组
 */
class SystemIcon extends Base
{
    public static $picFields = ['icon_img'];
    
    public function setIconImgAttr( $value )
    {
        return self::setImgVal($value);
    }
    public function getIconImgAttr( $value )
    {
        return self::getImgVal($value);
    }  
}