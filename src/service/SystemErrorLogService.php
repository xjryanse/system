<?php
namespace app\error\service;

use xjryanse\system\interfaces\MainModelInterface;
use think\facade\Request;
use Exception;
/**
 * 错误日志
 */
class SystemErrorLogService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemErrorLog';
    /**
     * 错误日志记录
     * @param Exception $e
     */
    public static function exceptionLog(Exception $e){
        $data['url']     = Request::url();
        $data['param']   = json_encode(Request::param(),JSON_UNESCAPED_UNICODE);
        $data['msg']     = $e->getMessage();
        $data['file']    = $e->getTrace()[0]['class'];
        $data['function']= $e->getTrace()[0]['function'];
        $data['line']    = $e->getLine();
        $data['code']    = $e->getCode();
        $data['trace']   = $e->getTraceAsString();
        $data['o_company_id']   = session('scopeCompanyId');
        $data['o_user_id']   = session('scopeUserId');
        $data['o_ip']    = Request::ip();
        //错误日志入库
        return self::save($data);
    }

}
