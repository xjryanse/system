<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 配置接口
 */
class SystemConfigsService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemConfigs';

    /**
     * 键值更新
     * @param type $key
     * @param type $value
     */
    public static function saveByKey($key, $value) {
        $con[] = ['key', '=', $key];
        $info = self::find($con);
        if ($info) {
            return self::getInstance($info['id'])->update(['value' => $value]);
        } else {
            $data['key'] = $key;
            $data['value'] = $value;
            return self::save($data);
        }
    }

    /*     * * */

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
     * 配置描述
     */
    public function fDesc() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 模块
     */
    public function fModule() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 分组：1杂项、2分享设置、3活动规则
     */
    public function fGroup() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 配置类型text显示文字 uplimage上传图片（wp_picture）其他，根据column_list表
     */
    public function fType() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 配置关键字:模块名+其他
     */
    public function fKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 【配置值】
     */
    public function fValue() {
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
