<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;

/**
 * 系统能力分组：业务板块
 */
class SystemAbilityGroupService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

// 静态模型：配置式数据表
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemAbilityGroup';
    // KeyModelTrait
    protected static $keyFieldName = 'group_key';

    use \xjryanse\traits\KeyModelTrait;

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    $abilityGroupDeptCount = SystemAbilityGroupDeptService::groupBatchCount('ability_group_id', $ids);
                    foreach ($lists as &$v) {
                        // 部门能力数
                        $v['abilityGroupDeptCount'] = Arrays::value($abilityGroupDeptCount, $v['ability_group_id'], 0);
                    }

                    return $lists;
                });
    }

    /**
     * 20230425:查询部门是否指定事项的管理者
     * @param type $deptId      部门
     * @param type $groupKey    分组key
     * @return type
     */
    public static function isDeptKeyManage($deptId, $groupKey) {
        $groupId = self::keyToId($groupKey);
        return SystemAbilityGroupDeptService::deptHasAbilityGroup($deptId, $groupId);
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
