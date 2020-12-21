<?php
namespace xjryanse\system\model;

use xjryanse\system\service\SystemFileService;
/**
 * 导入模板
 */
class SystemImportTemplate extends Base
{
    
    /**
     * 用户头像图标
     * @param type $value
     * @return type
     */
    public function getFileIdAttr( $value )
    {
        return $value ? SystemFileService::getInstance( $value )->get() : $value ;
    }
    /**
     * 修改器，文件带id只取id
     * @param type $value
     * @throws \Exception
     */
    public function setFileIdAttr( $value )
    {
        if((is_array($value)|| is_object($value)) && isset( $value['id'])){
            $value = $value['id'];
        }
        return $value;
    }
}