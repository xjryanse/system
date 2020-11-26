<?php
namespace xjryanse\system\service\columnlist;

/**
 * 枚举
 */
class Union extends Base
{
    /**
     * 获取option
     * @param type $optionStr
     */
    public static function getOption( $optionStr )
    {
        $arr = equalsToKeyValue( $optionStr , '&');
        if(isset( $arr[ FR_OPT_TPL_COND])){
            $arr[ FR_OPT_TPL_COND]      = json_decode($arr[ FR_OPT_TPL_COND],JSON_UNESCAPED_UNICODE );
        }
        if(isset( $arr[ FR_OPT_OPTION_COV])){
            $arr[ FR_OPT_OPTION_COV]    = json_decode($arr[ FR_OPT_OPTION_COV],JSON_UNESCAPED_UNICODE );
        }
        //主表的条件
        if(isset( $arr[ FR_OPT_MAIN_COND])){
            $arr[ FR_OPT_MAIN_COND] = json_decode($arr[ FR_OPT_MAIN_COND],JSON_UNESCAPED_UNICODE );
        }
        return $arr;
    }
}

