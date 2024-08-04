<?php
namespace xjryanse\system\model;

/**
 * 分类表
 */
class SystemCate extends Base
{
    //20230728 是否将数据缓存到文件
    public static $cacheToFile = true;
    
    public static $picFields = ['cate_img'];

    /**
     * 用户头像图标
     * @param type $value
     * @return type
     */
    public function getCateImgAttr($value) {
        return self::getImgVal($value);
    }

    /**
     * 图片修改器，图片带id只取id
     * @param type $value
     * @throws \Exception
     */
    public function setCateImgAttr($value) {
        return self::setImgVal($value);
    }

}