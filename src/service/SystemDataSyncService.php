<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 错误日志
 */
class SystemDataSyncService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemDataSync';

    /**
     * 获取末次同步信息
     * @param type $tableName
     * @return type
     */
    public function getLastSyncInfo($tableName) {
        $info = self::mainModel()->where('table_name', $tableName)->find();
        return $info;
    }

    /**
     * 获取末次同步信息
     * @param type $tableName
     * @return type
     */
    public function updateLastSyncInfo($tableName, $lastSaveId = '', $lastUpdateTime = '') {
        $data = [];
        if ($lastSaveId) {
            $data['last_save_id'] = $lastSaveId;
        }
        if ($lastUpdateTime) {
            $data['last_update_time'] = $lastUpdateTime;
        }
        if (self::mainModel()->where('table_name', $tableName)->value('id')) {
            $info = self::mainModel()->where('table_name', $tableName)->update($data);
        } else {
            $data['table_name'] = $tableName;
            $info = self::save($data);
        }
        return $info;
    }

    /**
     *
     */
    public function fId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 应用id
     */
    public function fAppId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 请求url
     */
    public function fUrl() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 请求参数
     */
    public function fParam() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 错误码
     */
    public function fCode() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 错误日志内容
     */
    public function fMsg() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 错误日志文件地址
     */
    public function fFile() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 方法名
     */
    public function fFunction() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 错误日志文件行数
     */
    public function fLine() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fTrace() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 操作公司id
     */
    public function fOCompanyId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 操作用户id
     */
    public function fOUserId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 操作ip
     */
    public function fOIp() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 是否已通知：0否，1是
     */
    public function fIsNoticed() {
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
