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

        return $service::find( $con ) ? : (new \StdClass());
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
    /**
     * 保存数据
     * table_name   =ydzb_goods
     * &key         =sale_type
     * &main_field  =goods_table_id
     * &label       =
     * &fields      =goods_name
     * &defaults    ={"goods_table":"ydzb_goods_trade_mark"}
     * &matches     ={"audit_status":"audit_status"}
     * 
     * @param type $data    原始的data
     * @param type $columnInfo  选项
     */
    public static function saveData( $data, $columnInfo )
    {
        $option     = $columnInfo['option'];
//        dump( $option );
        $resData                            = $data[ $columnInfo['name']];
        $resData[$option['key']]            = $columnInfo['name'];
        $resData[$option['main_field']]     = $data['id'];
        //默认值
        if(isset($option['defaults'])){
            $resData = array_merge( $option['defaults'], $resData );
        }
        //从父元素继承的值
        if(isset($option['matches'])){
            foreach( $option['matches'] as $key=>$value){
                $resData[ $key ] = isset( $data[$value] ) ? $data[$value] : '';
            }
        }
        
        //查询条件
        $con[]  = [ $option['key']          ,'=',$columnInfo['name']];
        $con[]  = [ $option['main_field']   ,'=',$data['id']        ];
        //查询是否有信息呢
        $service    = DbOperate::getService( $option['table_name'] );
        $info       = $service::find( $con );
        return $info 
                ? $service::getInstance( $info['id'] )->update( $resData )
                : $service::save( $resData );
    }
    
}

