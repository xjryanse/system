<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemErrorLogService;
use xjryanse\logic\Debug;
use xjryanse\logic\DataCheck;
use Exception;
use think\facade\Request;
use think\exception\Handle;
use think\Db;
/**
 * 异常处理类库
 */
class ExceptionLogic extends Handle
{
    use \xjryanse\traits\ResponseTrait;

    public function render(Exception $e)
    {
        // 20231123:全局返回data
        global $glRespData;
        // $pdo = SystemErrorLogService::mainModel()->getConnection()->getPdo();
        //校验是否在事务中
        // !DataCheck::isEmpty($pdo): 可否去除？？20220903
        if(SystemErrorLogService::mainModel()->inTransaction()){
            //事务回滚
            try{
                Db::rollback();
            } catch (\Exception $e){}
        }
        //错误日志记录
        SystemErrorLogService::exceptionLog($e);
        //有错误的用1
        if(Debug::isDebug()){
            return parent::render($e);
        } else {
            // 20230727
            $trace = [];
            if(Debug::isDevIp()){
                $trace['msg']     = $e->getMessage();
                $trace['file']    = $e->getFile();
                $trace['line']    = $e->getLine();
                $trace['trace']   = $e->getTrace();
            }
            return $this->codeReturn( $e->getCode() ? : 1 , $e->getMessage() ,$glRespData, $trace);
        }
    }
}