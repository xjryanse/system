<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Cachex;

/**
 * 系统单线程异步实施类库
 */
class SystemAsyncOperateService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemAsyncOperate';

    /**
     * 获取操作key
     * @return type
     */
    public static function operateKeys() {
        $res = Cachex::funcGet('SystemAsyncOperateService_operateKeys', function() {
                    return self::mainModel()->cache(86400)->column('distinct operate_key');
                });
        return $res;
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
     * 表名
     */
    public function fTableName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 末次执行取的记录id
     */
    public function fLastTableId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 末次执行取值时间
     */
    public function fLastRunTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 只取最近指定秒数内的记录，防误
     */
    public function fWithinSeconds() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 操作key
     */
    public function fOperateKey() {
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
