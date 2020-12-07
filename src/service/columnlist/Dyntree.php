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
        foreach( $arr as &$value ){
            $value = json_decode($value,JSON_UNESCAPED_UNICODE ) ? : $value;
        }
        
        //配套树状前端使用
        $lists = DbOperate::getService($arr['table_name'])::lists([],'','id,'.$arr['pid'].' as pId,concat('.$arr['value'].') as name' );
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

