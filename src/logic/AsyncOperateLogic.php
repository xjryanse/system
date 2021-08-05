<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemAsyncOperateService;
use xjryanse\logic\DbOperate;
use xjryanse\logic\Debug;
use think\Db;
/**
 * 异步执行逻辑
 */
class AsyncOperateLogic
{
    protected $classReflects = [

    ];
    /**
     * 构造函数，实例化传入映射类库
     * @param type $classReflects
     */
    public function __construct( $classReflects = [] ){
        if($classReflects){
            //映射类库
            $this -> classReflects = array_merge($this->classReflects,$classReflects);
        }
    }
    
    /**
     * 生成映射类库，用于注入本类进行实例化
     * @param type $methods     方法名
     * @param type $namespace   命名空间
     * @return string
     */
    public static function reflectClass ( $methods ,$namespace =  '\\app\\system\\AsyncOperate\\' )
    {
        $reflects = [];
        foreach( $methods as $method){
            $reflects[ $method ]    =  $namespace. ucfirst( $method );
        }
        return $reflects;
    }
    
    /**
     * 获取待处理任务列表
     * @return string
     */
    protected function getTodos()
    {
        $con[] = ['status','=',1]; 
        $lists = SystemAsyncOperateService::mainModel()->cache(1)->where($con)->select();
        return $lists;
    }
    /**
     * 主操作执行方法
     */
    public function do()
    {
        $todos = $this->getTodos();
        foreach( $todos as $v ){
            $lastRunId      = $v['last_table_id'];
            $lastRunTime    = $v['last_run_time'];
            $thisRunId      = DbOperate::lastId( $v['table_name'],[],1);
            $thisRunTime    = date('Y-m-d H:i:s');
            $tableName      = $v['table_name'];
            $operateKey     = $v['operate_key'];
            if(!$thisRunId){
                continue;
            }
            
            //先将本次执行时间写入数据库
            $data       = [];
            $data['last_table_id']   = $thisRunId;
            $data['last_run_time']   = $thisRunTime;
            SystemAsyncOperateService::getInstance( $v['id'] )->update( $data );
            //新增的数据 用id进行比较
            $this->addOperate($lastRunId, $thisRunId, $tableName, $operateKey);
            //更新的数据
            $this->updateOperate( $lastRunTime,$thisRunTime, $tableName, $operateKey);
        }
    }
    /**
     * 新增时执行操作类库
     */
    protected function addOperate( $lastRunId, $thisRunId, $tableName, $operateKey )
    {
        //新增的数据 用id进行比较
        $addLists       = self::withinAdds( $tableName, $lastRunId, $thisRunId );
        foreach( $addLists as $v){
            if(!isset( $this->classReflects[ $operateKey] )){
                continue;
            }
            call_user_func([ $this->classReflects[ $operateKey ] , 'asyncAddOperate'],$tableName, $v );
            usleep( 50 );
        }
    }
    /**
     * 更新时执行操作类库
     */
    protected function updateOperate( $lastRunTime, $thisRunTime, $tableName, $operateKey )
    {
        //新增的数据 用id进行比较
        $updateLists       = self::withinUpdates( $tableName, $lastRunTime ,$thisRunTime );
        Debug::debug( '$updateLists', $updateLists );
        Debug::debug( '$operateKey', $operateKey );
        foreach( $updateLists as $v){
            if(!isset( $this->classReflects[ $operateKey ] )){
                continue;
            }
            call_user_func([ $this->classReflects[ $operateKey ] , 'asyncUpdateOperate'],$tableName, $v );
            usleep( 50 );
        }
    }
    
    /**
     * 区间添加的记录
     * @param type $lastRunId   上次执行到哪条记录  
     * @param type $thisRunId   本次需要执行到哪条记录
     * @param type $con
     */
    protected static function withinAdds( $tableName, $lastRunId, $thisRunId ,$con = [])
    {
        $con[] = [ 'id','>',$lastRunId ];
        $con[] = [ 'id','<=',$thisRunId ];
        //最多只取12小时
        $con[] = ['create_time','>=',date('Y-m-d H:i:s',strtotime('-12 hours'))];
        return Db::table( $tableName )->where( $con )->cache(1)->select();
    }
    /**
     * 获取区间数据更新记录
     * @param type $lastRunId   上次执行到哪条记录
     * @param type $lastRunTime 上次执行的时间
     * @param type $con
     * @return type
     */
    protected static function withinUpdates( $tableName, $lastRunTime ,$thisRunTime, $con = [])
    {
//        $con[]  = ['id','<=',$lastRunId ];
        $con[]  = ['update_time','>=',$lastRunTime ];
        $con[]  = ['update_time','<',$thisRunTime ];
        //最多只取12小时
        $con[]  = ['update_time','>=',date('Y-m-d H:i:s',strtotime('-12 hours'))];
        return Db::table( $tableName )->where( $con )->select();
    }

}
