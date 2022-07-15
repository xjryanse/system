<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\system\logic\ImportLogic;
use xjryanse\logic\DbOperate;
use xjryanse\logic\Debug;
use xjryanse\logic\Arrays;
use xjryanse\logic\Arrays2d;
use think\Db;
/**
 * 导入临时文件
 */
class SystemImportTempService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemImportTemp';
    
    /**
     * 导入临时表的逻辑
     * @param type $tableName   目标表名
     * @param type $fileId      导入文件id
     * @param type $inputVal    预写数据
     */
    public static function importTemp($tableName,$fileId,$inputVal=[]){
        $importToTemp = SystemImportTempMatchService::importToTempColumns($tableName);
        $impData    = ImportLogic::fileGetArray( $fileId, $importToTemp ,30000 );     
        // 过滤转换一下
        $preData = Arrays::keyReplace( $inputVal, $importToTemp );
        $preData['table_name'] = $tableName;
        $preData['ident_key']  = uniqid();          //唯一识别key
        //$preData['input_val']  = json_encode($inputVal,JSON_UNESCAPED_UNICODE);   //输入值
        
        Db::startTrans();
            self::saveAll( $impData, $preData ,0);
        Db::commit();
        return count($impData);
    }
    
    public static function dataToTemp($tableName, $data, $inputVal = []){
        $importToTemp   = SystemImportTempMatchService::importToTempColumns($tableName);
        $shiftToKey = Arrays2d::shiftToKey( $data );
        $impData    = Arrays2d::keyReplace( $shiftToKey, $importToTemp );
        $preData = Arrays::keyReplace( $inputVal, $importToTemp );
        $preData['table_name'] = $tableName;
        $preData['ident_key']  = uniqid();          //唯一识别key
        //$preData['input_val']  = json_encode($inputVal,JSON_UNESCAPED_UNICODE);   //输入值
        
        Db::startTrans();
            self::saveAll( $impData, $preData ,0);
        Db::commit();
        return count($impData);
    }
    /**
     * 临时表到目标表
     */
    public static function tempToTarget(){
        $info = self::mainModel()->find();
        if(!$info){
            return false;
        }
        $service = DbOperate::getService($info['table_name']);
        if(method_exists( $service, 'clearImportDuplicate')){
            $service::clearImportDuplicate();
        }
        // 正式执行导入动作
        $con[] = ['table_name','=',$info['table_name']];
        $lists = self::mainModel()->alias('a')->master()->field('distinct a.md5, a.*')->where($con)->orderRand()->limit(50)->select();
        $listsArr = $lists ? $lists->toArray() : [];

        $tempToTarget = SystemImportTempMatchService::tempToTargetColumns($info['table_name']);
        //存储实际导入的数据
        $dataTemp = [];
        foreach($listsArr as $v ){
            $arrayReplace = Arrays::keyReplace($v, $tempToTarget);
            $inputVal     = json_decode($v['input_val'], JSON_UNESCAPED_UNICODE);
            $inputVal['company_id'] = $v['company_id'];
            $dataTemp[] = array_merge($arrayReplace,$inputVal);
        }
        Db::startTrans();
            // 如果有批量保存的特色方法，执行它
            if(method_exists( $service, 'batchSave')){
                $service::batchSave($dataTemp);
            } else {
                //执行通用的SAVEALL方法批量保存新数据
                $service::saveAll($dataTemp);
            }
            //删除原数据
            $ids = array_column($listsArr, 'id');
            $cond[] = ['id','in',$ids];
            self::mainModel()->where($cond)->delete();  
        Db::commit();
    }
    
    /**
     * 获取插队后的处理条件
     * @param type $con     原始条件
     * @param type $max     最大插队批次数量
     * @param type $limit   批次数
     */
    public static function withCutCon( $con = [],$max = 50,$limit = 3)
    {
        //根据ident_key，获取批次数量
        $res = self::mainModel()->where($con)
                ->field('ident_key,count(1) as total')
                ->group('ident_key')
                ->having("total <= ".$max)
                ->limit($limit)
                ->order('total')
                ->select();
        Debug::debug('根据ident_key获取批次量结果Sql', self::mainModel()->getLastSql());
        Debug::debug('根据ident_key获取批次量结果', $res);
        $identKeys  = array_column( $res ? $res->toArray() : [], 'ident_key');
        if($identKeys){
            $identKeys = array_column( $res->toArray(), 'ident_key');
            $con[] = ['ident_key','in', $identKeys ];
        }
        return $con;
    }
    /**
     * 重新设为待处理
     */
    public static function resetTodo()
    {
        $con[] = ['update_time','<',date('Y-m-d H:i:s',strtotime('-2 minute'))];
        $con[] = ['operate_status','=',1];
        $ids = self::ids( $con );
        if($ids){
            $con[] = ['id','in',$ids];
            self::mainModel()->where($con)->update(['operate_status'=>0]);
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
