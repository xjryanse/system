<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemErrorLogService;
use Exception;
use think\exception\Handle;
/**
 * 异常处理类库
 */
class ExceptionLogic extends Handle
{

    public function render(Exception $e)
    {
        //错误日志记录
        SystemErrorLogService::exceptionLog($e);
        //TODO::开发者对异常的操作
        //可以在此交由系统处理
        return parent::render($e);
    }
}