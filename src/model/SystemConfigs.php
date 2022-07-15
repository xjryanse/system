<?php
namespace xjryanse\system\model;

use xjryanse\system\service\SystemFileService;
use xjryanse\logic\Debug;
/**
 * 配置表
 */
class SystemConfigs extends Base
{
    /**
     * 值获取器，根据图片类型来区分
     */
    public function getValueAttr( $value, $data)
    {
        //上传图片
        if($data['type'] == FR_COL_TYPE_UPLIMAGE){
            Debug::debug('配置上传图片',$value);
            if($value){
                $info   = SystemFileService::mainModel()->where('id', $value )->field('id,file_path,file_path as rawPath')->cache(86400)->find();
                $value  = $info ? $info->toArray() : $value;
            }
            return $value ;
        }
        return $value;
    }
    /**
     * 值修改器，
     * @param type $value
     * @param type $data
     */
    public function setValueAttr( $value, $data )
    {
        //获取数据类型
        $type = "";
        if($data['id']){
            $info = self::get($data['id']);
            $type = $info ? $info['type'] : '';
        }
        //上传图片只取id
        if($type == FR_COL_TYPE_UPLIMAGE){
            return is_array($value) ? $value['id'] : $value ;
        }
        return $value;
    }
}