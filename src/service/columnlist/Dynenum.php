<?php
namespace xjryanse\system\service\columnlist;
use xjryanse\system\interfaces\ColumnListInterface;
use xjryanse\logic\Arrays;
/**
 * 枚举
 */
class Dynenum extends Base implements ColumnListInterface
{
    /**
     * 获取option
     * @param type $optionStr
     */
    public static function getOption( $optionStr,$data=[])
    {
        $arr = equalsToKeyValue( $optionStr , '&');
        foreach( $arr as &$value ){
            $value = json_decode($value,JSON_UNESCAPED_UNICODE ) ? : $value;
        }
        $con = [];
        if(isset( $arr['con']) && is_array($arr['con'])){
            foreach($arr['con'] as $key =>$value){
                if( $value && Arrays::value($data, $value) ){
                    $value = Arrays::value($data, $value);
                }
                //数组拆
                $con[] = [ $key,'in',explode(',',$value) ];
            }
        }
        $cache = isset($arr['cache']) ? true : false ;
        $arr['option']  = self::dynamicColumn( $arr['table_name'] , $arr['value'], $arr['key'], $con ,$cache );
        return $arr;
    }
    
    /**
     * 获取数据
     */
    public static function getData( $data, $option)
    {
        return isset($data[$option['name']]) ? $data[$option['name']] : '';
    }
    /**
     * 保存数据
     * @param type $data    原始的data
     * @param type $columnInfo  选项
     */
    public static function saveData( $data, $columnInfo )
    {
        
    }
    
}

