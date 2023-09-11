<?php

namespace xjryanse\system\service\companyAbility;

use xjryanse\system\service\SystemCompanyService;
use Exception;
/**
 * 分页复用列表
 */
trait PaginateTraits{
    
    
    /**
     * 20230806：公司选择分页
     */
    public static function paginateForCustomerSelect($con = []){
        $userId             = session(SESSION_USER_ID);
        $allowCompanyIds    = SystemCompanyService::userCompanyIds($userId);
        $con[]              = ['bind_company_id', 'in', $allowCompanyIds];
        
        $having = '';
        $field = "*";
        $withSum = true;
        return self::paginateRaw($con, 'id desc', 10, $having , $field, $withSum);
    }
    
    /**
     * 20230806：公司可选列表分页
     */
    public static function paginateForCustomerCanSelect($con = []){
        $con[] = ['has_try','=',0];
        $con[] = ['has_buy','=',0];
        // 20230902
        $con[] = ['status','=',1];
        return self::paginateForCustomerSelect($con);
    }
    /**
     * 待支付页面列表
     * @param type $con
     * @return type
     */
    public static function paginateForCustomerToPay($con = []){
        $con[] = ['has_try','=',1];
        $con[] = ['has_buy','=',0];
        return self::paginateForCustomerSelect($con);
    }
    
    /**
     * 已结算页面列表
     * @param type $con
     * @return type
     */
    public static function paginateForCustomerHasBuy($con = []){
        $con[] = ['has_buy','=',1];
        return self::paginateForCustomerSelect($con);
    }

}
