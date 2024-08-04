<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\system\service\SystemConditionService;
use xjryanse\user\service\UserAccountLogService;
use xjryanse\logic\Arrays;
use xjryanse\logic\Debug;
use xjryanse\logic\Datetime;

/**
 * 系统跳链key
 * 
 */
class SystemJumpKeyService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\StaticModelTrait;
    use \xjryanse\traits\ObjectAttrTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemJumpKey';

    public static function extraDetails( $ids ){
        return self::commExtraDetails($ids, function($lists) use ($ids){
            return $lists;
        },true);
    }
    
    public static function getUrl($key){
        $con[]  = ['jump_key','=',$key];
        $info   = self::staticConFind($con);
        return $info ? $info['jump_url'] : '';
    }
}
