<?php

namespace xjryanse\system\service\companyAbility;

use xjryanse\logic\Arrays;
use xjryanse\logic\Arrays2d;

/**
 * 分页复用列表
 */
trait CalTraits {

    /**
     * 校验是否可操作
     * 有买了，或者试用还没到期
     */
    protected static function calCanOperate($data) {
        if (Arrays::value($data, 'has_buy')) {
            return true;
        }
        return $data['has_try'] && date('Y-m-d H:i:s') < $data['try_finish_time'];
    }

    protected function calHasTry() {
        $lists = $this->objAttrsList('systemCompanyAbilityTryLog');
        return count($lists) ? 1 : 0;
    }

    protected function calTryFinishTime() {
        $lists = $this->objAttrsList('systemCompanyAbilityTryLog');
        $finishTimes = array_column($lists, 'try_finish_time');
        return $lists && $finishTimes ? max($finishTimes) : null;
    }

    protected function calHasPay() {
        $info = $this->get();
        if ($info && $info['prize'] == 0) {
            // 20230806:免费的默认已开通
            return 1;
        }
        $lists = $this->objAttrsList('financeStatementOrder');
        $con[] = ['has_settle', '=', 1];
        $listsSettle = Arrays2d::listFilter($lists, $con);
        $money = Arrays2d::sum($listsSettle, 'need_pay_prize');
        //已付金额大于等于设置金额
        return $money >= $info['prize'] ? 1 : 0;
    }
    
    public static function countCanSelect($con = []){
        $con[] = ['has_try','=',0];
        $con[] = ['has_buy','=',0];
        // 20230902
        $con[] = ['status','=',1];
        return self::where($con)->count();
    }
    /**
     * 计算可选功能(端口纬度)
     */
    public static function countCanSelectByCompany($companyId){
        $con[] = ['bind_company_id','in',$companyId];
        return self::countCanSelect($con);
    }
    
    /**
     * 计算可选功能(能力纬度)
     */
    public static function countCanSelectByAbility($abilityId){
        $con[] = ['ability_id','in',$abilityId];
        return self::countCanSelect($con);
    }
    /**
     * 统计待结算
     * @param type $con
     * @return type
     */
    public static function countToPay($con = []){
        $con[] = ['has_try','=',1];
        $con[] = ['has_buy','=',0];
        return self::where($con)->count();
    }
    /**
     * 计算可选功能(端口纬度)
     */
    public static function countToPayByCompany($companyId){
        $con[] = ['bind_company_id','in',$companyId];
        return self::countToPay($con);
    }
    
    /**
     * 计算可选功能(能力纬度)
     */
    public static function countToPayByAbility($abilityId){
        $con[] = ['ability_id','in',$abilityId];
        return self::countToPay($con);
    }
    
    /**
     * 统计待结算
     * @param type $con
     * @return type
     */
    public static function sumToPay($con = []){
        $con[] = ['has_try','=',1];
        $con[] = ['has_buy','=',0];
        return self::where($con)->sum('prize');
    }
    /**
     * 统计待结算(端口纬度)
     */
    public static function sumToPayByCompany($companyId){
        $con[] = ['bind_company_id','in',$companyId];
        return self::sumToPay($con);
    }
    
    /**
     * 统计待结算(能力纬度)
     */
    public static function sumToPayByAbility($abilityId){
        $con[] = ['ability_id','in',$abilityId];
        return self::sumToPay($con);
    }
}
