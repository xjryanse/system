<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\system\service\SystemCompanyService;
use xjryanse\system\service\SystemCompanyJobCertKeyService;
use xjryanse\user\service\UserService;
use xjryanse\logic\Arrays;
use xjryanse\logic\Debug;
use app\cert\service\CertService;

/**
 * 公司用户（员工表）
 */
class SystemCompanyUserService implements MainModelInterface {

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
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemCompanyUser';
    //直接执行后续触发动作
    protected static $directAfter = true;
    
    use \xjryanse\system\service\companyUser\DimTraits;
    use \xjryanse\system\service\companyUser\CalTraits;
    use \xjryanse\system\service\companyUser\FieldTraits;
    use \xjryanse\system\service\companyUser\TriggerTraits;
    
    /**
     * 获取公司旗下员工用户id
     * @param type $con
     * @return type
     */
    public static function companyUserIds($companyId) {
//        $con[] = ['company_id','=',$companyId];
//        return self::mainModel()->where($con)->column('distinct user_id');
        $listsAll = SystemCompanyService::getInstance($companyId)->objAttrsList('systemCompanyUser');
        return array_column($listsAll, 'user_id');
    }

    /**
     * 是否公司管理员
     * @param type $companyId
     * @param type $userId
     */
    public static function isCompanyUser($companyId, $userId) {
        $con[] = ['company_id', '=', $companyId];
        $con[] = ['user_id', '=', $userId];
        return self::staticConCount($con);
    }
    /**
     * 当前用户是否公司成员
     * @createTime 2024年4月18日
     * @param type $companyId
     * @param type $userId
     * @return type
     */
    public static function isMeCompanyUser() {
        $userId = session(SESSION_USER_ID);
        $companyId = session(SESSION_COMPANY_ID);

        $con[] = ['company_id', '=', $companyId];
        $con[] = ['user_id', '=', $userId];
        return self::staticConCount($con);
    }

    /**
     * 提取当前用户的岗位
     * 体检系统
     * @createTime 2023-10-15
     */
    public static function findByCurrentUser() {
        $userId = session(SESSION_USER_ID);
        $con    = [];
        $con[]  = ['user_id', '=', $userId];
        $data = self::where($con)->find();
        // 20231015构造数据，使前端能显示
        if(!$data){
            $data['user_id'] = $userId;
            // 控制前端显示
            $data['status'] = 1;
        }
        return $data;
    }

    /**
     * 20240102:保存用户的岗位信息
     * @param type $deptId
     * @param type $userId
     * @param type $roleKey
     * @return type
     */
    public static function saveUserJob($deptId, $userId, $roleKey){
        $uniqueData = [
            'dept_id' => $deptId,
            'user_id' => $userId,
        ];
        $jobId = SystemCompanyJobService::deptKeyToId($deptId, $roleKey);
        $saveData           = $uniqueData;
        $saveData['job_id'] = $jobId;
        
        $id = self::commGetIdEG($uniqueData);
        // 然后再更新
        return self::getInstance($id)->updateRam($saveData);
    }
    
    /**
     * 20240102：离职解绑
     * TODO:关联逻辑处理?
     */
    public function leave(){
        // TODO:关联逻辑
        
        return $this->deleteRam();
    }
    /**
     * 20240105:证件初始化
     */
    public function certInit(){
        $info           = $this->get();
        // 提取上岗资格证key
        $jobId          = Arrays::value($info, 'job_id');
        $certKeys       = SystemCompanyJobCertKeyService::jobCertKeys($jobId);
        $belongTable    = UserService::getTable();
        $belongTableId  = Arrays::value($info, 'user_id');
        
        CertService::certInit($belongTable, $belongTableId, $certKeys);
    }
    
}
