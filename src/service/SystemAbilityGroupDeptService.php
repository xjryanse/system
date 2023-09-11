<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 系统能力分组：业务板块
 */
class SystemAbilityGroupDeptService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

// 静态模型：配置式数据表
    use \xjryanse\traits\StaticModelTrait;
    use \xjryanse\traits\MiddleModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemAbilityGroupDept';
    protected static $middleFieldMapping = ['ability_group_id', 'dept_id'];

    /**
     * 部门是否有指定能力
     * @param type $deptId  
     * @param type $groupId
     * @return type
     */
    public static function deptHasAbilityGroup($deptId, $groupId) {
//        $con[] = ['ability_group_id','=',$groupId];
//        $con[] = ['dept_id','=',$deptId];
//        $info = self::staticConFind($con);
//        return $info? true: false;
        return self::middleMainHasSub($groupId, $deptId);
    }

    /**
     * 提取指定客户，指定事项的管理部门
     * 例如：XX单位，用车管理部门。
     * @param type $customerId      客户
     * @param type $abilityKey      能力key
     */
    public static function customerAbilityGroupKeyGetManageDeptId($customerId, $abilityKey) {
        $abilityGroupId = SystemAbilityGroupService::keyToId($abilityKey);
        $con[] = ['customer_id', '=', $customerId];
        $lists = self::middleMainSubList($abilityGroupId, $con);
        return $lists ? $lists[0]['dept_id'] : '';
    }

    /**
     *
     */
    public function fId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 应用名称
     */
    public function fName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 应用id
     */
    public function fAppid() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 应用密钥
     */
    public function fSecret() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 排序
     */
    public function fSort() {
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
     * 创建者，user表
     */
    public function fCreater() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 更新者，user表
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
