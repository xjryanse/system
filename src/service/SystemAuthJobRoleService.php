<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
// use xjryanse\view\service\ViewStaffService;
use xjryanse\logic\Arrays2d;
/**
 * 用户角色
 */
class SystemAuthJobRoleService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\MiddleModelTrait;
    use \xjryanse\traits\StaticModelTrait;
    use \xjryanse\traits\ObjectAttrTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemAuthJobRole';

    use \xjryanse\system\service\authJobRole\TriggerTraits;
    use \xjryanse\system\service\authJobRole\ListTraits;
    
    public static function extraDetails( $ids ){
        return self::commExtraDetails($ids, function($lists) use ($ids){
            return $lists;
        },true);
    }
//    
    /**
     * 保存用户的角色信息
     */
    public static function jobRoleIdSave($jobId,$roleIds){
        if(!$jobId){
            return false;
        }
        $dataArr = [];
        foreach($roleIds as $roleId){
            $dataArr[] = ['job_id'=>$jobId, 'role_id'=>$roleId];
        }
        //先删再加
        self::mainModel()->where('job_id',$jobId)->delete();
        self::saveAllRam($dataArr);
    }
    
    /**
     * 提取岗位旗下的角色
     * @param type $jobId
     */
    public static function dimRoleIdsByJobId($jobIds){
        $con[] = ['job_id','in',$jobIds];
        $arr = self::staticConList($con);
        $roleIds = Arrays2d::uniqueColumn($arr, 'role_id');
        return $roleIds;
    }


}
