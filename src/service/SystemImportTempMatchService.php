<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 导入临时文件
 */
class SystemImportTempMatchService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemImportTempMatch';

    use \xjryanse\system\service\importTempMatch\DataTraits;
    use \xjryanse\system\service\importTempMatch\DimTraits;

    /**
     * 获取导入表到临时表的数据转换数组
     */
    public static function importToTempColumns($tableName) {
        $con[] = ['table_name', '=', $tableName];
        return self::mainModel()->where($con)->column('temp_column', 'import_column');
    }

    /**
     * 获取临时表到目标表的数据转换数组
     */
    public static function tempToTargetColumns($tableName) {
        $con[] = ['table_name', '=', $tableName];
        return self::mainModel()->where($con)->column('target_column', 'temp_column');
    }

    /**
     * 导入表到目标表的数据转换数组
     * @param type $tableName
     * @return type
     */
    public static function importToTargetColumns($tableName) {
        $con[] = ['table_name', '=', $tableName];
        return self::mainModel()->where($con)->column('target_column', 'import_column');
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
     * 导入数据表名
     */
    public function fTableName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fFileId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * todo,doing,finish
     */
    public function fOpStatus() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 返回消息
     */
    public function fRespMessage() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 预导数据
     */
    public function fPreData() {
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
