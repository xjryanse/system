<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemAbilityPageKeyService;
/**
 * 能力逻辑（给外部公司调用）
 */
class AbilityLogic
{
    /**
     * 校验当前端口是否有某个能力
     * @param type $abiKey
     * @return type
     */
    public static function hasAbilityByKey($abiKey){
        // 3-提取系统全部有权能力key
        $abiArr = SystemAbilityPageKeyService::allAbilityArr();
        // 4-判断能力key是否在列表中
        return in_array($abiKey, $abiArr);
    }

}
