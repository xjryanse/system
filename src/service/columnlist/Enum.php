<?php
namespace xjryanse\system\service\columnlist;

/**
 * 枚举
 */
class Enum extends Base
{
    /**
     * 获取option
     * @param type $optionStr
     */
    public static function getOption( $optionStr )
    {
        return  $optionStr ? json_decode( $optionStr,true ) : [];
    }
}

