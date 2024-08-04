<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;
use xjryanse\logic\Arrays2d;
use xjryanse\logic\DbOperate;
use xjryanse\phyexam\service\PhyexamItemJobService;
use think\Db;

/**
 * 公司岗位表
 */
class SystemCompanyJobService implements MainModelInterface {

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
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemCompanyJob';

    use \xjryanse\system\service\companyJob\FieldTraits;
    use \xjryanse\system\service\companyJob\TriggerTraits;
    use \xjryanse\system\service\companyJob\ListTraits;
    
    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    $companyUserArr = SystemCompanyUserService::groupBatchSelect('job_id', $ids);
                    if(DbOperate::isTableExist(PhyexamItemJobService::getTable())){
                        $itemObjs       = PhyexamItemJobService::mainModel()->where([['job_id', 'in', $ids]])->select();
                        $itemIdArrs     = $itemObjs ? $itemObjs->toArray() : [];
                    }

                    // $roleArr = SystemAuthJobRoleService::groupBatchSelect('job_id', $ids, 'job_id,role_id');
                    
                    $roleArr    = self::roleArrList();
                    $roleArrObj = Arrays2d::fieldSetKey($roleArr, 'job_id');

                    foreach ($lists as &$v) {
                        // $v['$roleArrList']      = $roleArrObj;
                        // 岗位人数
                        $v['jobUserCount']      = count(Arrays::value($companyUserArr, $v['id'], []));
                        // 缺编人数
                        $v['remainUserCount']   = $v['plan_user_count'] - $v['jobUserCount'];
                        // 
                        $roleIdsStr             = Arrays::value($roleArrObj, $v['id']) ? $roleArrObj[$v['id']]['roleIds'] : '';
                        $v['roleIds']           = explode(',', $roleIdsStr);
                        $v['roleNameStr']       = Arrays::value($roleArrObj, $v['id']) ? $roleArrObj[$v['id']]['roleName'] : '';
                        $con    = [];
                        $con[]  = ['job_id', 'in', $v['id']];
                        $v['phyexamItemIds']    = array_column(Arrays2d::listFilter($itemIdArrs, $con), 'item_id');
                    }
                    return $lists;
                },true);
    }
    /**
     * 部门和key转id
     * @param type $key
     * @return type
     */
    public static function deptKeyToId($deptId, $key) {
        $con[] = ['dept_id', '=', $deptId];
        $con[] = ['role_key', '=', $key];
        $info = self::staticConFind($con);
        return $info ? $info['id'] : '';
    }
    /**
     * 角色列表
     * @return type
     */
    protected static function roleArrList(){
        $arr    = [];
        $arr[]  = ['table_name'=>'w_system_auth_job_role','alias'=>'tA'];
        $arr[]  = ['table_name'=>'w_user_auth_role','alias'=>'tB','join_type'=>'inner','on'=>'tA.role_id=tB.id'];

        $fields     = [];
        $fields[]   = 'tA.job_id'; 
        $fields[]   = 'group_concat(tB.name) AS roleName';
        $fields[]   = 'group_concat(tA.role_id) AS roleIds';
        $groupFields    = ['tA.job_id'];
        $sql            = DbOperate::generateJoinSql($fields,$arr,$groupFields);
        $roleArrList    = Db::query($sql);
        return $roleArrList;
    }
    /**
     * 20240304
     * @return type
     */
    public static function infowithMenu($param){
        $id = Arrays::value($param, 'id');
        
        $info = self::getInstance($id)->info();
        $info['menus'] = self::listMenu([]);
        
        return $info;
    }

    /**
     * id转字符串
     * @param type $ids
     */
    public static function idJobNames($ids){
        $con[] = ['id','in',$ids];
        $lists = self::staticConList($con);
        $arr = array_column($lists, 'job_name');
        return implode(',',$arr);
    }
}
