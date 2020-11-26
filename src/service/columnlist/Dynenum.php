<?php
namespace xjryanse\system\service\columnlist;

/**
 * 枚举
 */
class Dynenum extends Base
{
    /**
     * 获取option
     * @param type $optionStr
     */
    public static function getOption( $optionStr )
    {
        $arr = equalsToKeyValue( $optionStr , '&');
        $arr['option']  = self::dynamicColumn( $arr['table_name'] ,$arr['value'], $arr['key']);
        return $arr;
    }

}

