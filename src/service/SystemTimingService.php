<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\system\service\SystemTimingLogService;
use xjryanse\curl\Call;
use think\facade\Request;
/**
 * 系统定时器
 */
class SystemTimingService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemTiming';
    
    //获取待执行任务列表
    public static function todoIds()
    {
        //超时了 ： 间隔时间 小于 当前时间减末次执行时间的秒数
        return self::mainModel()->where('spacing','exp','<= TIMESTAMPDIFF(SECOND,last_run_time,now())')->where('status',1)->column('id');
    }
    
    public static function query( int $id )
    {
        $info       = self::mainModel()->get($id);
        //加锁更新
        $canQuery   = self::mainModel()->where('id',$id)
                ->where('spacing','exp','<= TIMESTAMPDIFF(SECOND,last_run_time,now())')
                ->update(['last_run_time'=>date('Y-m-d H:i:s')]);
        if($canQuery){
            $data['timing_id']  = $id;
            $data['url']        = $info['url'];
            $data['ip']         = Request::ip();
            //请求日志记录
            SystemTimingLogService::save($data);
            //执行请求动作
            Call::get( $info['url'] );
        }
    }
    
}
