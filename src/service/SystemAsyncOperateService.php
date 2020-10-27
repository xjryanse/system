<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
/**
 * 系统单线程异步实施类库
 */
class SystemAsyncOperateService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemAsyncOperate';
    
    /**
     * 区间添加的记录
     * @param type $lastRunId   上次执行到哪条记录  
     * @param type $thisRunId   本次需要执行到哪条记录
     */
    public static function getWithinAdds( $lastRunId, $thisRunId )
    {
        $con[] = [ 'id','>',$lastRunId];
        $con[] = [ 'id','<=',$thisRunId];
        return self::mainModel()->where($con)->select();
    }
    /**
     * 获取区间数据更新记录
     * @param type $lastRunId   上次执行到哪条记录
     * @param type $thisRunId   本次需要执行到哪条记录
     * @param type $lastRunTime 上次执行的时间
     */
    public static function getWithinUpdates( $lastRunId, $lastRunTime )
    {
        $con[]  = ['id','<',$lastRunId ];
        $con[]  = ['update_time','>=',$lastRunTime ];
        return self::mainModel()->where($con)->select();
    }
    
    
}
