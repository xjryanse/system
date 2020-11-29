<?php
namespace xjryanse\system\service\columnlist;
use xjryanse\system\interfaces\ColumnListInterface;
/**
 * 枚举
 */
class Subcheck extends Base implements ColumnListInterface
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

        return $arr;
    }
        
    /**
     * 获取数据
     */
    public static function getData( $data, $option)
    {
        return isset($data[$option['name']]) ? $data[$option['name']] : '';
    }
}

