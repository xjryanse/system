<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;
use xjryanse\logic\DbOperate;
use Exception;

/**
 * 
 */
class SystemMethodCheckService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemMethodCheck';

    /**
     * 根据方法id校验
     */
    public static function checkByMethodId( $methodId, $param )
    {
        $tableName = SystemMethodService::getInstance( $methodId )->fTableName();
        //有在方法列表的校验
        $con[] = ['method_id','=',$methodId ];
        $con[] = ['status','=',1];      //方便临时关闭校验，不使用的校验方法最好删除
        $rules = SystemMethodCheckService::lists( $con );      
        foreach( $rules as $rule){
            //需传的值，可空
            if($rule['check_type'] == 'require' && !isset( $param[ $rule['param'] ]) ){
                throw new Exception( $rule['notice']);
            }
            //必填的值，不可为空
            if($rule['check_type'] == 'must' && !Arrays::value($param, $rule['param'])){
                throw new Exception( $rule['notice']);
            }
            //必填的值，不可为空
            if($rule['check_type'] == 'unique' 
                    && self::isUnique($tableName, $rule['param'], Arrays::value($param, $rule['param']), Arrays::value($param, 'id') )){
                throw new Exception( $rule['notice']);
            }
        }
    }
    
    public static function isUnique($tableName, $field ,$value ,$id='')
    {
        $service = DbOperate::getService( $tableName );
        $con[] = [ $field ,'=',$value ];
        if($id){
            $con[] = ['id' ,'<>',$id ];
        }
        return $service::mainModel()->where( $con )->value('id');
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
     * 方法id
     */
    public function fMethodId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 参数名称
     */
    public function fParam() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 校验类型，1require,2must,3唯一
     */
    public function fCheckType() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 校验数据范围
     */
    public function fScope() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 校验失败返回提示
     */
    public function fNotice() {
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
