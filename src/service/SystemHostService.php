<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;
use xjryanse\logic\Debug;
use think\facade\Request;

/**
 * 域名信息
 */
class SystemHostService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

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
    /**
     * 域名主公司key
     * 20231116
     * @param type $host
     */
    public static function hostMainComKey($host = ''){
        if(!$host){
            $host = Request::host();
        }
        $info = self::getByHost($host);
        return $info ? $info['main_comkey'] : '';
    }

}
