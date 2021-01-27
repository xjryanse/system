<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemMethodService;
use xjryanse\system\service\SystemMethodCheckService;

/**
 * 数据导出逻辑
 */
class MethodCheckLogic
{
    /*
     * 数据校验，未校验通过则抛异常
     */
    public static function check( $param )
    {
        $methodId = SystemMethodService::getMethodId();
        if( $methodId ){
            SystemMethodCheckService::checkByMethodId($methodId, $param);
        }
    }

}
