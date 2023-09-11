<?php
namespace xjryanse\system\model;

use xjryanse\system\service\SystemFileService;
/**
 * 数据表
 */
class SystemColumn extends Base
{
    //20230728 是否将数据缓存到文件
    public static $cacheToFile = true;

    /**
     * 用户头像图标
     * @param type $value
     * @return type
     */
    public function getImportTplIdAttr( $value )
    {
        if(!$value){
            return $value;
        }

        $lists = SystemFileService::filesDb($value);
        return $lists ? $lists[0] : [];
        // 20230516:测试会死循环
        // return self::getImgVal($value);
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