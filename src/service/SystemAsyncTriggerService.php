<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\DbOperate;
use xjryanse\logic\DataCheck;
use xjryanse\logic\SnowFlake;
use think\Db;
use Exception;
/**
 * 系统单线程异步实施类库
 */
class SystemAsyncTriggerService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemAsyncTrigger';

    /**
     * 添加待执行任务
     */
    public static function addTask( $method, $fromTable, $fromTableId ){
        $methodAllows = ['save','update','delete'];
        if(!in_array($method, $methodAllows)){
            throw new Exception('method只能是'.implode(',',$methodAllows).'之一');
        }
        $data['method']         = $method;
        $data['from_table']     = $fromTable;
        $data['from_table_id']  = $fromTableId;
        $data['operate_status'] = 0;
        $data['id']             = SnowFlake::generateParticle();
        DataCheck::must($data, ['method','from_table','from_table_id']);
        return self::mainModel()->save($data);
    }
    /**
     * 获取待执行任务列表
     */
    public static function getTodos( $limit = 10 ){
        $con[] = ['operate_status','=',0];
        return self::mainModel()->where($con)->limit($limit)->select();
    }
    /**
     * 操作方法
     */
    public function operate(){
        $con[] = ['id','=',$this->uuid];
        $con[] = ['operate_status','=',0];  //加锁
        $resp = self::mainModel()->where($con)->update(['operate_status'=>1]);  //处理中
        if(!$resp){
            throw new Exception('数据'.$this->uuid.'非待处理状态');
        }
        //尝试进行数据处理
        try{
            $res = $this->doOperate();
            self::mainModel()->where('id',$this->uuid)->update(['operate_status'=>2]);  //处理完成
            return $res;
        } catch(\Exception $e){
            $message = $e->getMessage();
            self::mainModel()->where('id',$this->uuid)->update(['operate_status'=>3,'result'=>$message]);  //处理失败
            throw $e;
        }
    }
    /**
     * 开始执行
     */
    protected function doOperate(){
        $info           = $this->get();
        $service        = DbOperate::getService($info['from_table']);
        $operateMethod  = 'extraAfter'.ucfirst($info['method']);
        if(!$service){
            throw new Exception('操作类不存在'.$info['from_table']);
        }
        if(!method_exists( $service, $operateMethod)){
            throw new Exception('操作类'.$service.'的方法'.$operateMethod.'不存在');
        }

        //TODO统一
        $dataId = $info['from_table_id'];
        if($info['method'] == 'delete'){
            //实例
            Db::startTrans();
            $res = $service::getInstance( $dataId )->$operateMethod();
            Db::commit();
            return $res;
        } else {
            //静态
            $data = $service::getInstance( $dataId )->get();
            $dataArr = $data ? $data->toArray() : [];
            //数组传参处理
            Db::startTrans();
            $res = $service::$operateMethod($dataArr, $dataId);
            Db::commit();
            return $res;
        }        
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
    public function fMethod() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fFromTable() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 表名
     */
    public function fFromTableId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 末次执行取的记录id
     */
    public function fOperateStatus() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 末次执行取值时间
     */
    public function fResult() {
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
