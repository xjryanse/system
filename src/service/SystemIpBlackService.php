<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use think\facade\Request;
/**
 * 系统ip黑名单
 */
class SystemIpBlackService implements MainModelInterface {
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemIpBlack';

    /**
     * 校验系统ip，黑名单退出
     */
    public static function checkIpBlackExist()
    {
        $ip = Request::ip();
        $con[] = ['ip','=',$ip];
        if(self::find($con)){
            echo 'ip'.$ip.'已拉黑';
            exit;
        }        
    }
}
