<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;

/**
 * 系统额外配置（由客户/用户进行配置）
 */
class SystemExtraConfigsService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemExtraConfigs';

    /**
     * 20230501：获取客户的配置情况
     * @param type $customerId      客户编号
     * @param type $key             key
     */
    public static function customerKeyConf($customerId, $key) {
        $con[] = ['customer_id', '=', $customerId];
        $con[] = ['key', '=', $key];
        $info = self::staticConFind($con);
        return $info ? $info['value'] : '';
    }

}
