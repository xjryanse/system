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
        foreach( $arr as $key=>&$value ){
            $value = json_decode($value,JSON_UNESCAPED_UNICODE ) ? : $value;
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

