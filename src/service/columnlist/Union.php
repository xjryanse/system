<?php
namespace xjryanse\system\service\columnlist;
use xjryanse\system\interfaces\ColumnListInterface;
use xjryanse\logic\DbOperate;
/**
 * 枚举
 */
class Union extends Base implements ColumnListInterface
{
    /**
     * 获取数据
     */
    public static function getData( $data, $option)
    {
        $tableNmae  = $option['option']['table_name'];      //目标表名
        $key        = $option['option']['key'];             //key存入字段名
        $mainField  = $option['option']['main_field'];      //主键存入字段名
        //获取服务类库
        $service    = DbOperate::getService( $tableNmae );
        $con[] = [ $key ,'=',$option['name'] ];  //字段名存入key
        $con[] = [ $mainField ,'=',$data['id'] ];  //字段名存入key
        
        return $service::find( $con ) ? : [];
    }
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

        return $arr;
    }
}

