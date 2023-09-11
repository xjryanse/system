<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Cachex;
use think\facade\Request;
use Exception;

/**
 * 系统ip黑名单
 */
class SystemIpBlackService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemIpBlack';

    /**
     * 校验系统ip，黑名单退出
     */
    public static function checkIpBlackExist() {
        $ip = Request::ip();

        $ipBlacks = self::staticConColumn('ip');

        //判断是否黑
        if ($ipBlacks && in_array($ip, $ipBlacks)) {
            throw new Exception('ip' . $ip . 'in black');
        }
    }

}
