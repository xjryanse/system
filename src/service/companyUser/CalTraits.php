<?php

namespace xjryanse\system\service\companyUser;

use xjryanse\system\service\SystemCompanyJobService;
/**
 * 
 */
trait CalTraits{
    /**
     * 计算用户的岗位名称
     * @return type
     */
    public static function calUserJobName($userId){
        $jobId = self::calUserJobId($userId);

        return SystemCompanyJobService::getInstance($jobId)->fJobName();
    }
    
    /**
     * 计算用户的岗位名称
     * @return type
     */
    public static function calUserJobId($userId){
        $con    = [];
        $con[]  = ['user_id','=',$userId];
        return self::where($con)->cache(1)->value('job_id');
    }
    

}
