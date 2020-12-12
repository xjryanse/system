<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 分类表
 */
class SystemCateService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemCate';

    /**
     * 分组筛选
     * @param type $groupKey    key
     * @param type $con         条件
     * @param type $field       字段
     * @return type
     */
    public function listsByGroup($groupKey, $con = [], $field = "*") {
        $con[] = ['group_key', '=', $groupKey];
        $res = self::mainModel()->where($con)->field($field)->select();
        return $res;
    }

    /*     * ***************** */

    /**
     *
     */
    public function fId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fAppId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fCompanyId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 分组key
     */
    public function fGroupKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 分类key
     */
    public function fCateKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 分类名
     */
    public function fCateName() {
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
