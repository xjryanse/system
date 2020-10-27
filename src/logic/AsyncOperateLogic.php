<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemAsyncOperateService;
use xjryanse\logic\DbOperate;
use think\Db;
/**
 * 异步执行逻辑
 */
class AsyncOperateLogic
{
    
    protected $classReflects = [
        //发送模板消息
        'sendTemplateMsg'   => '\\app\\webapi\\logic\\OrdersTmAuthPlateLogic',    //商标授权
        //发送socket消息
        'sendSocketMsg'     => '\\app\\webapi\\logic\\OrdersTmRentPlateLogic',    //商标租用
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
     * 获取待处理任务列表
     * @return string
     */
    protected function getTodos()
    {
        $con[] = ['status','=',1]; 
        $lists = SystemAsyncOperateService::mainModel()->where($con)->select();
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
            $thisRunId      = DbOperate::lastId( $v['table_name']);
            $thisRunTime    = date('Y-m-d H:i:s');
            $tableName      = $v['table_name'];
            $operateKey     = $v['operate_key'];
            //先将本次执行时间写入数据库
            $data       = [];
            $data['last_table_id']   = $thisRunId;
            $data['last_run_time']   = $thisRunTime;
            SystemAsyncOperateService::getInstance( $v['id'] )->update( $data );
            //新增的数据 用id进行比较
            $this->addOperate($lastRunId, $thisRunId, $tableName, $operateKey);
            //更新的数据
            $this->updateOperate($lastRunId, $lastRunTime, $tableName, $operateKey);
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
    protected function updateOperate( $lastRunId,$lastRunTime,$tableName, $operateKey )
    {
        //新增的数据 用id进行比较
        $updateLists       = self::withinUpdates( $tableName, $lastRunId, $lastRunTime );

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
        return Db::table( $tableName )->where( $con )->select();
    }
    /**
     * 获取区间数据更新记录
     * @param type $lastRunId   上次执行到哪条记录
     * @param type $lastRunTime 上次执行的时间
     * @param type $con
     * @return type
     */
    protected static function withinUpdates( $tableName, $lastRunId, $lastRunTime ,$con = [])
    {
        $con[]  = ['id','<',$lastRunId ];
        $con[]  = ['update_time','>=',$lastRunTime ];
        return Db::table( $tableName )->where( $con )->select();
    }

}