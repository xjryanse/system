<?php
namespace xjryanse\system\service\columnlist;

use xjryanse\system\interfaces\ColumnListInterface;
/**
 * 枚举
 */
class Check extends Base implements ColumnListInterface
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

        $arr['option']  = self::dynamicLists( $arr['table_name'] );
//        dump($arr);
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

