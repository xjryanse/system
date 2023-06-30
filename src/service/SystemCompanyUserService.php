<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\system\service\SystemCompanyService;
use xjryanse\logic\Arrays2d;
use xjryanse\logic\Debug;

/**
 * 公司用户（员工表）
 */
class SystemCompanyUserService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemCompanyUser';

    /**
     * 获取公司旗下员工用户id
     * @param type $con
     * @return type
     */
    public static function companyUserIds($companyId) {
//        $con[] = ['company_id','=',$companyId];
//        return self::mainModel()->where($con)->column('distinct user_id');
        $listsAll = SystemCompanyService::getInstance($companyId)->objAttrsList('systemCompanyUser');
        return array_column($listsAll, 'user_id');
    }

    /**
     * 是否公司管理员
     * @param type $companyId
     * @param type $userId
     */
    public static function isCompanyUser($companyId, $userId) {
        $con[] = ['company_id', '=', $companyId];
        $con[] = ['user_id', '=', $userId];
        return self::staticConCount($con);
    }

    /**
     *
     */
    public function fId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公司名称
     */
    public function fCompanyId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    public function fUserId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公司编号
     */
    public function fRole() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公司简称
     */
    public function fIsManager() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公司类型
     */
    public function fRealname() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 成立时间
     */
    public function fJob() {
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
