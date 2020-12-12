<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 导出日志记录
 */
class SystemExportLogService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemExportLog';

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
     * 导出信息说明
     */
    public function fInfo() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fModule() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 导出文件名称
     */
    public function fFileName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 文件路径
     */
    public function fFilePath() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 文件表头
     */
    public function fFiledJson() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 查询sql语句
     */
    public function fSearchSql() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 0待生成；1生成中；2已生成；3已下载
     */
    public function fExpStatus() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fFinishTime() {
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
