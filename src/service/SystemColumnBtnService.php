<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\user\service\UserAuthRoleBtnService;
use think\facade\Request;

/**
 * 数据表按钮
 */
class SystemColumnBtnService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;


    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemColumnBtn';

    public static function listWithCov($con = [], $order = '', $field = "*", $cache = -1) {
        $lists = self::lists($con, $order, $field, $cache);
        foreach ($lists as $k => &$v) {
            $v = self::btnCov($v);
        }
        return $lists;
    }

    public static function btnCov(&$btnInfo) {
        //当前session的用户id
        $userId = session(SESSION_USER_ID);
        $tmp = $btnInfo['url'];
        $tmp = str_replace('{$sessionUserId}', $userId, $tmp);

        $tmp .= strstr($tmp, '?') ? '&' . 'comKey=' . Request::param('comKey', '') : '/comKey/' . Request::param('comKey', '');
        $btnInfo['url'] = in_array($btnInfo['place'], ['head', 'list']) ? $tmp : $btnInfo['url'];
        $btnInfo['param'] = str_replace('{$sessionUserId}', $userId, $btnInfo['param']) ? json_decode(str_replace('{$sessionUserId}', $userId, $btnInfo['param']), true) : [];
        $btnInfo['show_condition'] = str_replace('{$sessionUserId}', $userId, $btnInfo['show_condition']) ? json_decode(str_replace('{$sessionUserId}', $userId, $btnInfo['show_condition'])) : [];

        return $btnInfo;
    }

    /*     * ******** */

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
     * 表id
     */
    public function fColumnId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * block_id，具体板块的操作按钮
     */
    public function fBlockId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 按钮名
     */
    public function fName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 操作确认
     */
    public function fConfirm() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * jump:本页跳链，
      mainpop本页弹窗，
      blank新窗口
      listpop:列表弹窗
      listoperate:列表操作（带确认）
     */
    public function fOType() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 按钮样式：eg:layui-btn-normal
     */
    public function fStyle() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 按钮图标：eg:fa-user
     */
    public function fIcon() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 地址:内嵌参数拿主表数据
     */
    public function fUrl() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 参数json
     */
    public function fParam() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 显示条件
     */
    public function fShowCondition() {
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
