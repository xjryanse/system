<?php

namespace xjryanse\system\service\companyUser;

/**
 * 
 */
trait DimTraits{
    /*
     * 提取用户的岗位列表
     */
    public static function dimJobIdsByUserId($userId){
        $con    = [];
        $con[]  = ['user_id','in',$userId];
        return self::column('job_id',$con);
    }
}
