<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays2d;
/**
 * 公司岗位表
 */
class SystemCompanyJobCertKeyService implements MainModelInterface {

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
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemCompanyJobCertKey';

    /**
     * 岗位取资格证key
     * @param type $jobId
     */
    public static function jobCertKeys($jobId){
        $con    = [];
        $con[]  = ['job_id','=',$jobId];
        $lists  = self::staticConList($con);
        
        return Arrays2d::uniqueColumn($lists, 'cert_key');
    }

}
