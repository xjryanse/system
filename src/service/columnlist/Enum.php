<?php
namespace xjryanse\system\service\columnlist;
use xjryanse\system\interfaces\ColumnListInterface;
/**
 * 枚举
 */
class Enum extends Base implements ColumnListInterface
{
    /**
     * 获取option
     * @param type $optionStr
     */
    public static function getOption( $optionStr )
    {
        return  $optionStr ? json_decode( $optionStr,true ) : [];
    }
        
    /**
     * 获取数据
     */
    public static function getData( $data, $option)
    {
        return isset($data[$option['name']]) ? $data[$option['name']] : '';
    }
}

