<?php
namespace xjryanse\system\model;

use xjryanse\system\service\SystemFileService;
/**
 * 数据表
 */
class SystemColumn extends Base
{
    /**
     * 用户头像图标
     * @param type $value
     * @return type
     */
    public function getImportTplIdAttr( $value )
    {
        return $value ? SystemFileService::getInstance( $value )->get() : $value ;
    }
    /**
     * 图片修改器，图片带id只取id
     * @param type $value
     * @throws \Exception
     */
    public function setImportTplIdAttr( $value )
    {
        if((is_array($value)|| is_object($value)) && isset( $value['id'])){
            $value = $value['id'];
        }
        return $value;
    }
}