<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 配置接口
 */
class SystemConfigsService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemConfigs';

    /**
     * 键值更新
     * @param type $key
     * @param type $value
     */
    public static function saveByKey( $key,$value)
    {
        $con[] = ['key','=',$key];
        $info = self::find( $con );
        if($info){
            return self::getInstance($info['id'])->update(['value'=>$value]);
        } else {
            $data['key']    = $key;
            $data['value']  = $value;
            return self::save($data);
        }
    }
}
