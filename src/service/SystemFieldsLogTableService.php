<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;

/**
 * 
 */
class SystemFieldsLogTableService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemFieldsLogTable';
    
    /**
     * 表记录
     * @param type $tableName   
     */
    public static function tableLog( $tableName , $preData, $afterData )
    {
        //根据表名，取需要记录的日志
        $con[] = ['table_name','=',$tableName];
        $res = self::lists( $con );
        foreach( $res as $key => $value){
            //字段不存在，或修改前后字段相等，继续
            if(!isset($afterData[$value['field_name']]) || $preData[$value['field_name']] == $afterData[$value['field_name']]){
                continue;
            }
            $data = [];
            $data['table_name'] = $tableName;
            $data['field_name'] = $value['field_name'];
            $data['record_id']  = $value['id'];         //记录的id
            $data['before_val'] = json_encode($preData);
            $data['after_val']  = json_encode($afterData);
            
            $beforeValue    = Arrays::value( $preData, $value['field_name'] );
            $afterValue     = Arrays::value( $afterData, $value['field_name'] );
            
            $data['before_value']   = is_array($beforeValue) || is_object($beforeValue) ? Arrays::value($beforeValue, 'id') : $beforeValue ;
            $data['after_value']    = is_array($afterValue) || is_object($afterValue) ? Arrays::value($afterValue, 'id') : $afterValue ;
            //日志记录
            SystemFieldsLogService::save( $data );
        }

        return true;
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
