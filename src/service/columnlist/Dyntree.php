<?php
namespace xjryanse\system\service\columnlist;
use xjryanse\system\interfaces\ColumnListInterface;
use xjryanse\logic\DbOperate;
use xjryanse\logic\Arrays;
use xjryanse\logic\Debug;
/**
 * 枚举
 */
class Dyntree extends Base implements ColumnListInterface
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
        //配套树状前端使用
        $con[] = ['status','=',1];
        if(isset( $arr['con']) && is_array($arr['con'])){
            foreach($arr['con'] as $key =>$value){
                if( $value && Arrays::value($data, $value) ){
                    $value = Arrays::value($data, $value);
                }
                //数组拆
                $con[] = [ $key,'in',explode(',',$value) ];
            }
        }
        Debug::debug( 'Dyntree的getOption的sql', DbOperate::getService($arr['table_name'])::mainModel()->where($con)->buildSql());

        $lists = DbOperate::getService($arr['table_name'])::lists($con,'','id,'.$arr['pid'].' as pId,concat('.$arr['value'].') as name' );
        $arr['option']  = $lists ? $lists->toArray() : [] ;

        return $arr;
    }
        
    /**
     * 获取数据
     */
    public static function getData( $data, $option)
    {
        $con   = [];
        $con[] = [$option['option']['main_field'],'=',$data['id']];
        //20210908修复 $option['name'] 为 $option['option']['to_field']
        $rr = self::dynamicColumn( $option['option']['to_table'], $option['option']['to_field'], 'id',$con );
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

