<?php
namespace xjryanse\system\service\columnlist;

use xjryanse\system\interfaces\ColumnListInterface;
use xjryanse\logic\Arrays;
/**
 * 枚举
 */
class Check extends Base implements ColumnListInterface
{
    /**
     * 获取option
     * @param type $optionStr
     */
    public static function getOption( $optionStr,$data=[])
    {
        $arr = equalsToKeyValue( $optionStr , '&');
        foreach( $arr as $key=>&$value ){
            $value = json_decode($value,JSON_UNESCAPED_UNICODE ) ? : $value;
        }
        $con            = isset($arr['con']) ? $arr['con'] : [];
        $orderBy        = Arrays::value($arr, 'orderBy','');
        $arr['option']  = self::dynamicLists( $arr['table_name'] ,$con, $orderBy);
        //$arr['option']  = self::dynamicColumn( $arr['table_name'] , $arr['value'], $arr['key'], $con );
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
    /**
     * 保存数据
     * @param type $data    原始的data
     * @param type $columnInfo  选项
     */
    public static function saveData( $data, $columnInfo )
    {
        return self::midDeleteAndSave( $columnInfo['option']['to_table'] 
                , $columnInfo['option']['main_field'] 
                , $columnInfo['option']['to_field']
                , $data['id']
                , $data[$columnInfo['name']]);  //一维数组，存了一堆id        
    }
}

