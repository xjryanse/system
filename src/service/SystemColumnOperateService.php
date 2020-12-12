<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 操作表
 */
class SystemColumnOperateService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemColumnOperate';

    /*     * ***** */

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
     *
     */
    public function fColumnId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 操作key，添加，编辑，删除，导出
     */
    public function fOperateKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 操作名称
     */
    public function fOperateName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 异步地址
     */
    public function fAjaxUrl() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 模板文件id
     */
    public function fTplId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 页面告知
     */
    public function fPageNotice() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 请求确认告知
     */
    public function fAjaxNotice() {
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
