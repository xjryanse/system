<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\system\service\SystemConditionService;
use xjryanse\user\service\UserAccountLogService;
use xjryanse\logic\Arrays;
use xjryanse\logic\Debug;
use xjryanse\logic\Datetime;

/**
 * 分类表
 */
class SystemRuleService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemRule';

    /**
     * 规则是否达成
     * 包含了期间段内达成次数进行校验
     */
    public function isRuleReached($data) {
        $info = $this->get();
        $itemType = Arrays::value($info, 'rule_type');
        $itemKey = Arrays::value($info, 'rule_key');
        $ruleTimes = Arrays::value($info, 'rule_times');
        //TODO抽离优化
        if ($info['rule_type'] == 'score') {
            $con = [];
            $con[] = ['user_id', '=', $data['userId']];
            $con[] = ['create_time', '>=', $data['fromTime']];
            $con[] = ['create_time', '<=', $data['toTime']];
            $con[] = ['change_cate', '=', $itemKey];
            $currentCount = UserAccountLogService::count($con);
            if ($currentCount >= $ruleTimes) {
                return false;
            }
        }
        $res = SystemConditionService::isReachByItemKey($itemType, $itemKey, $data);
        Debug::debug("规则" . $this->uuid . "是否已达成?", $res);

        return $res;
    }

    public static function getByTypeKey($ruleType, $ruleKey) {
        $con[] = ['rule_type', '=', $ruleType];
        $con[] = ['rule_key', '=', $ruleKey];
        return self::find($con);
    }

    /**
     * 20220814规则取id
     */
    public static function ruleTypeIds($ruleType) {
        $con[] = ['rule_type', '=', $ruleType];
        $con[] = ['status', '=', 1];
        $res = self::staticConList($con);
        return array_column($res, 'id');
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
