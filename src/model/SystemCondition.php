<?php
namespace xjryanse\system\model;

/**
 * 达成条件表
 */
class SystemCondition extends Base
{
    /**
     * 获取判定条件
     */
    public function getJudgeCondAttr( $value )
    {
        return $value ? json_decode($value,true) : [];
    }

}