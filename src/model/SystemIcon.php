<?php
namespace xjryanse\system\model;

/**
 * 图标组
 */
class SystemIcon extends Base
{
    /**
     * 用户头像图标
     * @param type $value
     * @return type
     */
    public function getIconImgAttr($value) {
        return self::getImgVal($value);
    }

    /**
     * 图片修改器，图片带id只取id
     * @param type $value
     * @throws \Exception
     */
    public function setIconImgAttr($value) {
        return self::setImgVal($value);
    }

}