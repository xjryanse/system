<?php
namespace xjryanse\system\service\columnlist;
use xjryanse\system\interfaces\ColumnListInterface;
use xjryanse\logic\Arrays;
use think\Db;
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
        //超过100条，通过ajax取数据
        //可以外部设置data_ajax = 0，表示全部加载
        if(!isset($arr['data_ajax'])){
            $arr['data_ajax'] = Db::table( $arr['table_name'] )->where( $con )->count() >= 100 ? 1:0;
        }
        //需ajax取数，不出数据，否则出数据
        if( $arr['data_ajax'] && isset($data['id'])){
            $con[] = ['id','in',$data['id']];
        }
        //非ajax，全部查，有传id，也查，是ajax，不查
        if(!$arr['data_ajax']){
            $option = self::dynamicColumn( $arr['table_name'] , $arr['value'], $arr['key'], $con ,$cache );
        } else if( isset($data['id']) ){
            $option = self::dynamicColumn( $arr['table_name'] , $arr['value'], $arr['key'], $con ,$cache );
        } else {
            $option = [];
        }
        
        $arr['option']  = $option;
//        $arr['option']  = self::dynamicColumn( $arr['table_name'] , $arr['value'], $arr['key'], $con ,$cache );
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

