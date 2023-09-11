<?php

namespace xjryanse\system\service;

use app\circuit\service\CircuitService;
use app\circuit\service\CircuitBusService;
//后勤人员
use app\view\service\ViewLogisticsService;
//司机
use app\view\service\ViewDriverService;
use app\bus\service\BusService;
use xjryanse\customer\service\CustomerUserService;
use xjryanse\system\service\SystemAbilityGroupDeptService;
use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;

/**
 * 公司部门
 */
class SystemCompanyDeptService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemCompanyDept';

    public static function extraDetails($ids) {
        //数组返回多个，非数组返回一个
        $isMulti = is_array($ids);
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $con[] = ['id', 'in', $ids];
        $lists = self::selectX($con);
        // 仓库出入明细数
        $busCounts = BusService::groupBatchCount('dept_id', $ids);
        // 员工数
        $staffCounts = SystemCompanyUserService::groupBatchCount('dept_id', $ids);
        // 岗位数
        $jobCounts = SystemCompanyJobService::groupBatchCount('dept_id', $ids);
        // 司机数
        $driverCounts = ViewDriverService::groupBatchCount('dept_id', $ids);
        // 后勤数
        $logisticsCounts = ViewLogisticsService::groupBatchCount('dept_id', $ids);
        // 线路数
        $circuitCounts = CircuitService::groupBatchCount('dept_id', $ids);
        // 班次数
        $circuitBusCounts = CircuitBusService::groupBatchCount('dept_id', $ids);
        // 客户用户数
        $customerUserCounts = CustomerUserService::groupBatchCount('dept_id', $ids);
        // 分组能力数
        $abilityGroupCount = SystemAbilityGroupDeptService::groupBatchCount('dept_id', $ids);

        foreach ($lists as &$v) {
            //仓库出入明细数
            //$v['storeChangeDtlCounts']   = Arrays::value($storeChangeDtlArr, $v['id'],0);
            //部门车辆数
            $v['busCounts'] = Arrays::value($busCounts, $v['id'], 0);
            //部门岗位数
            $v['jobCounts'] = Arrays::value($jobCounts, $v['id'], 0);
            //客户绑定数
            $v['customerUserCounts'] = Arrays::value($customerUserCounts, $v['id'], 0);
            //部门员工数
            $v['staffCounts'] = Arrays::value($staffCounts, $v['id'], 0);
            //司机数
            $v['driverCounts'] = Arrays::value($driverCounts, $v['id'], 0);
            //后勤数
            $v['logisticsCounts'] = Arrays::value($logisticsCounts, $v['id'], 0);
            //线路数
            $v['circuitCounts'] = Arrays::value($circuitCounts, $v['id'], 0);
            //班次数
            $v['circuitBusCounts'] = Arrays::value($circuitBusCounts, $v['id'], 0);
            // 部门的能力分组数
            $v['abilityGroupCount'] = Arrays::value($abilityGroupCount, $v['id'], 0);
        }

        return $isMulti ? $lists : $lists[0];
    }

    /**
     * 20220615 ,客户id，取部门id
     */
    public static function customerIdGetDeptId($customerId) {
        $con[] = ['bind_customer_id', '=', $customerId];
        $info = self::staticConFind($con);
        return $info ? $info['id'] : '';
    }

    /**
     *
     */
    public function fId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 
     */
    public function fCompanyId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    public function fBindCustomerId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    public function fDeptName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 
     */
    public function fManagerId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 状态(0禁用,1启用)
     */
    public function fStatus() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 有使用(0否,1是)
     */
    public function fHasUsed() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 锁定（0：未锁，1：已锁）
     */
    public function fIsLock() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 锁定（0：未删，1：已删）
     */
    public function fIsDelete() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 备注
     */
    public function fRemark() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 创建者
     */
    public function fCreater() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 更新者
     */
    public function fUpdater() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 创建时间
     */
    public function fCreateTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 更新时间
     */
    public function fUpdateTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

}
