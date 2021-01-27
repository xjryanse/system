<?php
namespace xjryanse\system\model;

/**
 * 
 */
class SystemImportAsync extends Base
{
    /**
     * 文件
     * @param type $value
     * @return type
     */
    public function getFileIdAttr($value) {
        return self::getImgVal($value);
    }
}