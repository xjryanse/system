<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 
 */
class SystemFieldsInfoService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemFieldsInfo';

    public static function getInfoFields( $tableName )
    {
        $con[] = ['table_name','=',$tableName];
        $con[] = ['status','=',1];
        $con[] = ['is_extra','=',1];
        return self::mainModel()->where($con)->cache(86400)->column('relative_table','field_name');
    }

    /**
     * 限制了关联表记录删除的字段
     * @param type $relativeTable
     * @return type
     */
    public static function relativeDelFields( $relativeTable )
    {
        $con[] = ['relative_table','=',$relativeTable];
        $con[] = ['status','=',1];
        $con[] = ['is_relative_del','=',1];
        return self::lists($con, '', 'id,table_name,field_name,relative_table', 86400);
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
     * 布局名称
     */
    public function fTableName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 排序
     */
    public function fFieldName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 关联表名
     */
    public function fRelativeTable() {
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
