<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\system\service\SystemColumnBtnService;
use xjryanse\system\service\SystemColumnBlockTableFieldsService;
use xjryanse\logic\Cachex;
/**
 * 系统块表
 */
class SystemColumnBlockService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemColumnBlock';

    public static function listsInfo($con = array()) {
        $info = self::lists($con);
        foreach ($info as &$v) {
            $con1 = [];
            $con1[] = ['block_id', '=', $v['id']];
            $tablesInfo = SystemColumnBlockTableFieldsService::mainModel()->where($con1)->cache(86400)->column("*", "table_name");
            $v['tablesInfo'] = $tablesInfo;
            //获取操作按钮
            $v['btnInfo'] = SystemColumnBtnService::mainModel()->where($con1)->cache(86400)->select();
            foreach ($v['btnInfo'] as &$vv) {
                $vv = SystemColumnBtnService::btnCov($vv);
            }
        }
        return $info;
    }

    /*     * **** */

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
     * 字段id
     */
    public function fColumnId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 标题
     */
    public function fTitle() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 主表字段
     */
    public function fMainField() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 关联表名
     */
    public function fTableName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 关联字段
     */
    public function fTableField() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 关联类型：1v1 一对一;1vn 一对多
     */
    public function fUnionType() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 展示方式：list 列表; info 信息
     */
    public function fShowType() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 12等分宽度：1,2,3,4,5
     */
    public function fBlockWidth() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * vh
     */
    public function fBlockHeight() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 展示字段，逗号分隔
     */
    public function fFields() {
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
