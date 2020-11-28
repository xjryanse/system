<?php
namespace xjryanse\system\service\columnlist;
use xjryanse\system\interfaces\ColumnListInterface;
use xjryanse\logic\DbOperate;
/**
 * 枚举
 */
class Dyntree extends Base implements ColumnListInterface
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
        
        //配套树状前端使用
        $arr['option']  = DbOperate::getService($arr['table_name'])::lists([],'','id,'.$arr['pid'].' as pId,concat('.$arr['value'].') as name' );

        return $arr;
    }
        
    /**
     * 获取数据
     */
    public static function getData( $data, $option)
    {
        $con   = [];
        $con[] = [$option['option']['main_field'],'=',$data['id']];
        $rr = self::dynamicColumn( $option['option']['to_table'], $option['name'], 'id',$con );
        return array_values($rr);        
    }
}
