<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Strings;

/**
 * 系统文案清单
 */
class SystemTxtTplService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;


// 静态模型：配置式数据表
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemTxtTpl';

    /**
     * 20220614
     * @param type $key
     * @return type
     */
    public static function getByKey($key, $data = []) {
        $con[] = ['txt_key', '=', $key];
        $res = self::find($con);
        $res['content'] = Strings::dataReplace($res['content'], $data);
        for ($i = 1; $i <= 6; $i++) {
            $res['text' . $i] = Strings::dataReplace($res['text' . $i], $data);
        }

        return $res ? $res->toArray() : [];
    }

    /*     * *************************************** */

    /**
     *
     */
    public function fId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 应用名称
     */
    public function fName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 应用id
     */
    public function fAppid() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 应用密钥
     */
    public function fSecret() {
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
