<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;
use xjryanse\logic\Debug;

/**
 * 域名信息
 */
class SystemHostService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemHost';

    /**
     * 2023-01-15 根据域名取绑定公司
     * @param type $host
     * @return type
     */
    public static function getByHost($host) {
        $con[] = ['host', '=', $host];
        $con[] = ['status', '=', 1];
        $info = self::staticConFind($con);
        Debug::debug(__CLASS__ . '$con', $con);
        Debug::debug(__CLASS__ . '$info', $info);

        return $info;
    }

}
