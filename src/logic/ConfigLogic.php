<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemConfigsService;
/**
 * 配置逻辑
 */
class ConfigLogic
{
    /**
     * 按键名获取一个配置项
     * @param type $name
     * @return type
     */
    public static function config($name,$module='')
    {
        $configs = self::getConfigs($module);
        return empty($configs[$name]) ? '' : $configs[$name] ;
    }
    /**
     * 【使用中】获取配置项数组
     * @return type
     */
    public static function getConfigs( $module='' )
    {
        $con        = [];
        if( $module ){
            $con[]  = [ 'module', '=', $module ];
        }
        $configs = SystemConfigsService::lists( $con );

        return array_column($configs ? $configs->toArray() : [], 'value', 'key');        
    }
    

}
