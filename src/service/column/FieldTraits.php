<?php

namespace xjryanse\system\service\column;

/**
 * 分页复用列表
 */
trait FieldTraits{
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
     * 后台控制器，采用前台的模块名
     */
    public function fController() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 表单键名
     */
    public function fTableKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 说明
     */
    public function fName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fTableName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 表单样式：1竖排；2横排
     */
    public function fFormStyle() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fOrderBy() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 行颜色条件（根据字段状态显示不同颜色）
     */
    public function fColorCon() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 年月字段
     */
    public function fYearmonthField() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 资源位置：
      ''当前
      'user'用户,
      'access'权限,
      'system'系统,
      'busi'业务
     */
    public function fSource() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 块宽度
     */
    public function fBlockWidth() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 是否开启主表的联表删除:0否；1是
     */
    public function fUniDel() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 是否允许联表被删：0否；1是
     */
    public function fUniDeleted() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 分类字段名
     */
    public function fCateFieldName() {
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
