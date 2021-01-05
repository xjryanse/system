<?php
namespace xjryanse\system\interfaces;

/**
 * 资源控制器接口
 */
interface AccountLogicInterface
{
    /**
     * 入账
     * @param type $ownerId     所有人id
     * @param type $accountType 账户类型
     * @param type $value       入账值
     * @param type $data        其他数据
     */
    public static function doIncome( $ownerId, $accountType, $value, $data= [] );
    /**
     * 出账
     * @param type $ownerId     所有人id
     * @param type $accountType 账户类型
     * @param type $value       出账值
     * @param type $data        其他数据
     * @param type $permitNegative  是否允许账户负值
     */
    public static function doOutcome( $ownerId, $accountType, $value, $data= [] ,$permitNegative = false );
}
