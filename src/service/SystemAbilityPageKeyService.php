<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\BaseSystem;
use xjryanse\logic\Arrays2d;
use xjryanse\logic\CacheX;
/**
 * 系统能力对应页面清单
 */
class SystemAbilityPageKeyService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

// 静态模型：配置式数据表
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemAbilityPageKey';

    /**
     * 请求接口获取能力数据
        "pageKeyList":[
            {
                "page_key":"wSlFinanceStaffFeeTabsMy",
                "hasAuth":1
            },
            {
                "page_key":"pGiftThingList",
                "hasAuth":0
            }
        ],
        "abilityKeyArr":[
            "baoPrePassAutoNoticeCustomer",
        ]
     * @return type
     */
    private static function allAuthData(){
        return CacheX::funcGet(__METHOD__, function(){
            $param['bind_company_id'] = session(SESSION_COMPANY_ID);
            return BaseSystem::baseSysGet('/webapi/Universal/allAuthList', $param);
        },true);
    }
    
    /**
     * 20230726:所有页面
     * @return int
     */
    public static function allPageList(){
        $authData = self::allAuthData();
        return $authData ? $authData['pageKeyList'] : []; 
    }
    /**
     * 业务端使用
     * @return type
     */
    public static function allAbilityArr(){
        $authData = self::allAuthData();
        return $authData ? $authData['abilityKeyArr'] : []; 
    }
    /**
     * 20230726:从本系统取列表（主系统用）
     */
    public static function thisPageList($companyId){
        // companyId提取abilityid,
        // 20230809:优化
        $abilityIds     = SystemCompanyAbilityService::canOperateAbilityIds($companyId);
        // abilityid提取pageKey
        // $allPageKeys    = array_unique(self::column('page_key'));
        // 20230908:只提取需要支付的能力来判断
        $allPageKeys    = SystemCompanyAbilityService::allPageKeysByCompanyId($companyId);

        $conAbi[]       = ['ability_id','in',$abilityIds];
        $abiPageKeys = array_unique(self::column('page_key',$conAbi));
        // 拼接返回数组
        $arr = [];
        foreach($allPageKeys as $v){
            $arr[] = ['page_key'=>$v,'hasAuth'=>in_array($v,$abiPageKeys) ? 1 : 0];
        }
        return $arr;
    }

}
