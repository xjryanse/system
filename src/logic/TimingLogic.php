<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemTimingService;
use xjryanse\system\service\SystemTimingLogService;
use think\facade\Cache;
/**
 * 定时器逻辑
 */
class TimingLogic
{
    use \xjryanse\traits\InstTrait;

    protected $cacheName='timingRequestTime';
     
    /**
     * 方案一：服务端执行
     * 每分钟执行一次，每秒进行定时器扫描，
     * 碰上需要执行的拿出来执行
     */
    public function local()
    {
        if (!isset($_SERVER['SHELL'])) {
            exit;
        }
        $this->execute();
    }
    /**
     * 方案二：客户端执行
     * 每分钟执行一次，每秒进行定时器扫描，
     * 碰上需要执行的拿出来执行
     */
    public function index()
    {
        if ( time() - Cache::get( $this->cacheName ) < 60 ) {
            return (time() - Cache::get( $this->cacheName ));
        }
        Cache::set( $this->cacheName , time());
        set_time_limit(90);
        $this->execute();
    }

    /**
     * 执行代码
     */
    private function execute()
    {
        $second = 60;
        while($second >0 ){
            $list = SystemTimingService::todoIds();
            foreach($list as $v){
                SystemTimingService::query( $v );
            }
            $second--;
            sleep(1);
        }
    }
    /**
     * 超过3天的记录删除
     */
    public function deleteExpire()
    {
        $con[]  = ['create_time','<=',date('Y-m-d H:i:s',strtotime('-3 day'))];
        SystemTimingLogService::mainModel()->where( $con )->delete();
    }
}
