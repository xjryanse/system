<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\system\logic\ConfigLogic;
use think\facade\Request;

/**
 * 访问日志
 */
class SystemLogService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemLog';
    /**
     * 请求本系统
     */
    public static function log() {
        if(!ConfigLogic::config('queryLogOpen')){
            return false;
        }
        try {
            $data['type']       = 1;  //请求本系统
            $data['ip']         = Request::ip();
            $data['url']        = Request::url();
            $data['header']     = json_encode(Request::header(), JSON_UNESCAPED_UNICODE);
            $data['param']      = json_encode(Request::param(), JSON_UNESCAPED_UNICODE);
            $data['module']     = Request::module();
            $data['controller'] = Request::controller();
            $data['action']     = Request::action();
            self::save($data);
        } catch (\Exception $e) {
            //不报异常，以免影响访问
        }
    }
    /**
     * 调用其他系统
     * @param type $url         url接口
     * @param type $header      header请求头
     * @param type $param       param参数
     * @param type $response    response返回结果
     */
    public static function outLog($url, $header, $param, $response, $data = []) {
        try {
            $data['type']       = 2;  //请求本系统
            $data['url']        = $url;
            $data['header']     = is_array( $header ) ? json_encode($header, JSON_UNESCAPED_UNICODE) : $header;
            $data['param']      = is_array( $param ) ? json_encode($param, JSON_UNESCAPED_UNICODE) : $param;
            $data['response']   = is_array( $response ) ? json_encode($response, JSON_UNESCAPED_UNICODE) : $response;
            self::save($data);
        } catch (\Exception $e) {
            //不报异常，以免影响访问
        }
    }
    /**
     *
     */
    public function fId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 应用id
     */
    public function fAppId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公司id
     */
    public function fCompanyId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 访问ip
     */
    public function fIp() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fUrl() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 请求头部
     */
    public function fHeader() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 请求参数
     */
    public function fParam() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 访问模块
     */
    public function fModule() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 访问控制器
     */
    public function fController() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 访问方法
     */
    public function fAction() {
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
