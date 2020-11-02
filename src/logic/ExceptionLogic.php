<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemErrorLogService;
use Exception;
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
        //错误日志记录
        SystemErrorLogService::exceptionLog($e);
        //校验是否在事务中
        if(SystemErrorLogService::mainModel()->inTransaction()){
            //事务回滚
            Db::rollback();
        }
        return $this->codeReturn( $e->getCode(), $e->getMessage() );
        //TODO::开发者对异常的操作
        //可以在此交由系统处理
//        return parent::render($e);
    }
}