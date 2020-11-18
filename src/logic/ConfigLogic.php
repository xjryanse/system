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
    
    /******以下用于后台管理配置******/
    public static function getColumn( $group="",$module="" )
    {
        $lists = SystemConfigsService::lists( self::configCond( $group,$module ) );
        $listInfo = [];
        foreach($lists as $v){
            $tmp = [];
            $tmp['label']   = $v['desc'];
            $tmp['name']    = $v['key'];
            $tmp['type']    = $v['type'];
            $tmp['form_col']= 6;
            $tmp['is_edit'] = 1;
            $listInfo[] = $tmp;
        }
        
        $column['table_name'] = "ydzb_system_configs";
        $column['listInfo']   = $listInfo;
        return $column;
    }
    /**
     * 查询条件
     * @param type $group   分组
     * @param type $module  模块
     * @return string
     */
    protected function configCond( $group="",$module="")
    {
        $con  = [];
        if( $group ){
            $con[] = ['group','=',$group ];
        }
        if( $module ){
            $con[] = ['module','=',$group ];
        }
        return $con;
    }
}
