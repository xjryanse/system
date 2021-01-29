<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\DbOperate;

/**
 * 表单预加载联动数据
 */
class SystemFormLinkageService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemFormLinkage';

    /**
     * 获取联表数据
     * @param type $tableName   表名
     * @param type $linkField   联表字段
     * @param type $item        数据
     */
    public static function getLinkage( $tableName, $linkField, &$item ){
        if(!isset($item[$linkField])){
            return false;
        }
        $linkages = self::tableFieldLinkages($tableName, $linkField);
        foreach( $linkages as $linkage){
            $con    = $item['condition'] ? json_decode($item['condition'],JSON_UNESCAPED_UNICODE) : [];
            $con[]  = [$linkage['source_link_field'],'=',$item[$linkField]];
            
            $sourceService = DbOperate::getService($linkage['source_table']);
            if( !$sourceService ){
                continue;                    
            }
            $sourceInfo = $sourceService::find($con);
            //当前表目标字段，赋值为来源表来源字段
            $item[ $linkage['target_field'] ]   = $sourceInfo && isset($sourceInfo[ $linkage['source_field']]) 
                    ? $sourceInfo[ $linkage['source_field']]
                    : "";
        }
        return $item;
    }

    /**
     * 表名字段取联动数组
     * @param type $tableName   表名
     * @param type $linkField   字段
     */
    protected static function tableFieldLinkages($tableName, $linkField )
    {
        $con[] = ['table_name','=',$tableName];
        $con[] = ['link_field','=',$linkField];
        return self::lists( $con );
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
     * 当前表联动触发字段
     */
    public function fLinkField() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 当前表目标字段
     */
    public function fTargetField() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 目标字段数据来源表名
     */
    public function fSourceTable() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 目标字段数据来源表字段
     */
    public function fSourceField() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 来源表中与联动字段对应的字段名，一般为id
     */
    public function fSourceLinkField() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 来源表其它过滤条件
     */
    public function fCondition() {
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
