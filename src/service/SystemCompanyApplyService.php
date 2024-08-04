<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;
use xjryanse\logic\Arrays2d;
use think\facade\Request;
use Exception;

/**
 * 公司岗位表
 */
class SystemCompanyApplyService implements MainModelInterface {

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
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemCompanyApply';

    use \xjryanse\system\service\companyApply\DoTraits;
    use \xjryanse\system\service\companyApply\TriggerTraits;
    
    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
            foreach($lists as &$v){
                // 控制前端按钮显示
                $v['hasNewCompany'] = $v['new_company_id'] ? 1 : 0;
            }

            return $lists;
        },true);
    }
    /**
     * 20231206：提取当前用户的申请记录
     */
    public static function userGet(){
        $cate = Request::param('cate');
        if(!$cate){
            throw new Exception('未指定系统类型cate');
        }
        $con    = [];
        $con[]  = ['user_id','=',session(SESSION_USER_ID)];
        $con[]  = ['cate','=',$cate];
        
        $info = self::where($con)->find();
        // 标识当前用户是否有上报记录，用于控制前端显示
        $info['userHasData'] = $info ? 1 : 0;
        return $info;
    }
    
}
