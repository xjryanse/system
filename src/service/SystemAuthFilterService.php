<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
// use xjryanse\view\service\ViewStaffService;
use xjryanse\logic\Arrays;
use xjryanse\logic\DbOperate;

/**
 * 
 */
class SystemAuthFilterService implements MainModelInterface {

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
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemAuthFilter';

//    use \xjryanse\system\service\authJobRole\TriggerTraits;
//    use \xjryanse\system\service\authJobRole\ListTraits;
//    
    public static function extraDetails( $ids ){
        return self::commExtraDetails($ids, function($lists) use ($ids){
            return $lists;
        },true);
    }

    /**
     * 20240316：提取有权限的数据key
     */
    public static function calFilterIdData($key){
        $con    = [['key','=',$key]];
        $info   = self::staticConFind($con);
        $tableName = Arrays::value($info, 'auth_table_name');
        $fieldName = Arrays::value($info, 'auth_field_name');
        $valueName = Arrays::value($info, 'auth_value_name');
        
        $service = DbOperate::getService($tableName);
        $conV = [[$fieldName,'in',session(SESSION_USER_ID)]];

        $valueIds = $service::where($conV)->column('distinct '.$valueName);
        return $valueIds;
    }
    
}
