<?php
namespace xjryanse\system\service\columnlist;
use xjryanse\system\interfaces\ColumnListInterface;
/**
 * 枚举
 */
class Dynenum extends Base implements ColumnListInterface
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
    
    /**
     * 获取数据
     */
    public static function getData( $data, $option)
    {
        
        
        
    }
}

