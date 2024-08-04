<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\system\logic\ConfigLogic;
use xjryanse\logic\Strings;
use xjryanse\logic\Arrays;
use think\facade\Request;
use Exception;
use app\third\baidu\BaiduIp;

/**
 * 错误日志
 */
class SystemErrorLogService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;


    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemErrorLog';

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    foreach ($lists as &$v) {
                        // ip地址位置
                        $v['ipPlace'] = BaiduIp::place($v['o_ip']);
                        $v['ipOwner'] = BaiduIp::owner($v['o_ip']);
                    }
                    return $lists;
                });
    }

    /**
     * 错误日志记录
     * @param Exception $e
     */
    public static function exceptionLog(Exception $e, $strict = false) {
        if (!$strict && !ConfigLogic::config('errLogOpen')) {
            return false;
        }
        $data['url'] = Request::url();
        $data['param'] = json_encode(Request::param(), JSON_UNESCAPED_UNICODE);
        $data['msg'] = $e->getMessage();
//        $data['file'] = $e->getTrace()[0]['class'];
//        $data['function'] = $e->getTrace()[0]['function'];
        $data['file']       = Arrays::value($e->getTrace()[0], 'class');
        $data['function']   = Arrays::value($e->getTrace()[0], 'function');
        $data['line']       = $e->getLine();
        $data['code']       = $e->getCode();
        $data['trace']      = Strings::keepLength($e->getTraceAsString(), 8192);
        $data['o_company_id'] = session(SESSION_COMPANY_ID);
        $data['o_user_id']  = session(SESSION_USER_ID);
        $data['o_ip']       = Request::ip();
        //错误日志入库
        try {
            self::save($data);
        } catch (\Exception $e) {
            
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
     * 请求url
     */
    public function fUrl() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 请求参数
     */
    public function fParam() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 错误码
     */
    public function fCode() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 错误日志内容
     */
    public function fMsg() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 错误日志文件地址
     */
    public function fFile() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 方法名
     */
    public function fFunction() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 错误日志文件行数
     */
    public function fLine() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fTrace() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 操作公司id
     */
    public function fOCompanyId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 操作用户id
     */
    public function fOUserId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 操作ip
     */
    public function fOIp() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 是否已通知：0否，1是
     */
    public function fIsNoticed() {
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
