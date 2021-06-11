<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays2d;
use xjryanse\logic\Datetime;

/**
 * 浏览日志
 */
class SystemScanLogService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemScanLog';

    public static function log($scanItem, $scanItemId, $userId, $data = []) {
        $data['scan_item'] = $scanItem;
        $data['scan_item_id'] = $scanItemId;
        $data['user_id'] = $userId;

        return self::save($data);
    }

    /**
     * 按日期范围，统计浏览人数
     */
    public static function staticsByDayScope($startTime, $endTime) {
        $startTime1 = date('Y-m-d 00:00:00', strtotime($startTime));
        $endTime1 = date('Y-m-d 23:59:59', strtotime($endTime));

        $con[] = ['create_time', '>=', $startTime1];
        $con[] = ['create_time', '<', $endTime1];

        $data = self::mainModel()->where($con)
                ->field("date_format( create_time, '%Y-%m-%d' ) dat, count( * ) coun")
                ->group("date_format( create_time, '%Y-%m-%d' )")
                ->select();
        $dates = Datetime::getWithinDate($startTime1, $endTime1);
        $res = Arrays2d::noValueSetDefault($data ? $data->toArray() : [], 'dat', $dates, 'coun', 0);
        return $res;
    }

    /**
     * 获取最近一个推荐人id
     */
    public static function lastRecUserId()
    {
        $con[] = ['rec_user_id','<>',''];
        $lastRecUserId = self::mainModel()->where($con)->order('id desc')->value('rec_user_id');
        return $lastRecUserId;
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
     * 推荐用户
     */
    public function fRecUserId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 浏览用户
     */
    public function fUserId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 浏览项目：商标、网店
     */
    public function fScanItem() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 浏览项目id
     */
    public function fScanItemId() {
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
