<?php
namespace xjryanse\system\interfaces;

/**
 * 异步执行的数据表逻辑
 */
interface AsyncOperateInterface
{
    /**
     * 添加执行异步逻辑【适配AsyncOperateLogic类】
     * @param type $tableName   表名
     * @param type $data        数据
     */
    public static function asyncAddOperate( $tableName, $data );

    /**
     * 更新执行异步逻辑【适配AsyncOperateLogic类】
     * @param type $tableName   表名
     * @param type $data        数据
     */
    public static function asyncUpdateOperate( $tableName, $data );
}
