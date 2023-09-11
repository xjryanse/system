<?php
namespace xjryanse\system\interfaces;

/**
 * 第三方资源日志表
 */
interface ThirdFinanceLogInterface
{
    /**
     * 将当前表的数据，写入财务账户
     * @param type $log
     */
    public static function addFinanceAccountLog($log);
    /**
     * 使用账单号进行收付款记录查询
     * @param type $statementId
     */
    public static function payQuery($statementId);

}
