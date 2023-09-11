<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;


/**
 * 系统ip白名单
 */
class SystemIpWhiteService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemIpWhite';
    
    public static function ipList($thingKey){
        $con[] = ['thing_key','=',$thingKey];
        $lists = self::staticConList($con);
        return array_column($lists, 'ip');
    }
    /**
     * ip是否允许
     * @param type $thingKey
     * @param type $ip
     */
    public static function isIpAllowed($thingKey, $ip){
        $ipList = self::ipList($thingKey);
        return in_array($ip, $ipList);
    }

}
