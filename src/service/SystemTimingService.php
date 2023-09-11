<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\system\service\SystemTimingLogService;
use xjryanse\curl\Call;
use think\facade\Request;
use xjryanse\system\logic\ConfigLogic;
use xjryanse\logic\Cachex;

/**
 * 系统定时器
 */
class SystemTimingService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemTiming';

    //获取待执行任务列表
    public static function todoIds() {
        //超时了 ： 间隔时间 小于 当前时间减末次执行时间的秒数
        return self::mainModel()->where('spacing', 'exp', '<= TIMESTAMPDIFF(SECOND,last_run_time,now())')->where('status', 1)->column('id');
    }
    /**
     * 网络io调用
     * @param int $id
     */
    public static function query(int $id) {
        $info = self::mainModel()->get($id);
        //加锁更新
        $canQuery = self::mainModel()->where('id', $id)
                ->where('spacing', 'exp', '<= TIMESTAMPDIFF(SECOND,last_run_time,now())')
                ->update(['last_run_time' => date('Y-m-d H:i:s')]);
        if ($canQuery) {
            $data['timing_id'] = $id;
            $data['url'] = $info['url'];
            $data['ip'] = Request::ip();
            // 是否开启定时器日志
            if(ConfigLogic::config('timingLogOpen')){
                //请求日志记录
                SystemTimingLogService::save($data);
            }
            //20211016，测试，以减少io
            //20220328;可执行服务器命令，也可跨站点调用
            if($info['module'] && $info['controller'] && $info['action']){
                //执行服务器命令
                // $command="cd /www/wwwroot/tenancy.xiesemi.cn/public;php index.php ".$info['module']."/".$info['controller']."/".$info['action'];
                // dirname(__DIR__,3) 打印结果 "/www/wwwroot/tenancy.xiesemi.cn"
                $command="cd ".dirname(__DIR__,5)."/public;php index.php ".$info['module']."/".$info['controller']."/".$info['action'];
                $fp = popen($command,"r");
                pclose($fp);
            } else if($info['url']) {
                //跨站点调用，执行请求动作
                Call::get($info['url']);
            }
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
     * 模块
     */
    public function fModule() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 控制器
     */
    public function fController() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 方法
     */
    public function fAction() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 定时任务名称
     */
    public function fName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 定时任务说明
     */
    public function fDescribe() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 最少间隔秒数(s)
     */
    public function fSpacing() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fUrl() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 定时器末次调用时间
     */
    public function fLastRunTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 程序末次执行时间（相应脚本中写入）
     */
    public function fLastExecuteTimestamp() {
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
