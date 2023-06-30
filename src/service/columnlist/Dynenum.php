<?php
namespace xjryanse\system\service\columnlist;

use xjryanse\system\interfaces\ColumnListInterface;
use xjryanse\logic\Arrays;
use xjryanse\logic\DbOperate;
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
        if(!$optionStr){
            return [];
        }
        $arr = equalsToKeyValue( $optionStr , '&');
        foreach( $arr as &$value ){
            $value = json_decode($value,JSON_UNESCAPED_UNICODE ) ? : $value;
        }
        $con = [];
        if(isset( $arr['con']) && is_array($arr['con'])){
            foreach($arr['con'] as $k =>$v){
                if( $value && Arrays::value($data, $v) ){
                    $value = Arrays::value($data, $v);
                }
                //数组拆
                $con[] = [ $k,'in',explode(',',$v) ];
            }
        }
        $cache = isset($arr['cache']) ? true : false ;
        //可以外部设置data_ajax = 0，表示全部加载
        if(!isset($arr['data_ajax'])){
            //超过100条，通过ajax取数据
            //$arr['data_ajax'] = Db::table( $arr['table_name'] )->where( $con )->cache(1)->count() >= 100 ? 1:0;
            //20220303新添加了数据之后，会出bug，调整为全部按接口加载
            $arr['data_ajax'] = 1;
        }
        //需ajax取数，不出数据，否则出数据
        if( $arr['data_ajax'] && isset($data['id']) && $data['id']){
            //20220317,如果只有空id，不查
            $con[] = ['id','in',$data['id']];
        }
        //非ajax，全部查，有传id，也查，是ajax，不查
        if(!$arr['data_ajax'] || (isset($data['id']) && $data['id'])){
            $orderBy = Arrays::value($arr, 'orderBy');
            $option = self::dynamicColumn( $arr['table_name'] , $arr['value'], $arr['key'], $con ,$orderBy,$cache );
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
    
    /**
     * 2022-12-18 二维数组的返回方式组装
     * 适用于select2的场景
     * @param type $lists           二维数组
     * @param type $fieldId         key
     * @param type $tableField      value
     * @param type $uniCov          联动转换
     * ['name'=>'value']
     */
    public static function dataResArr2d($lists,$fieldId,$tableField, $uniCov = []){
        $dataArr = [];
        foreach($lists as &$value){
            $dataArr[] = [
                'id'            => $value[$fieldId],
                'text'          => $value[$tableField],
                'uniSetValue'   => Arrays::keyReplace($value, $uniCov)
            ];
        }
        return $dataArr;
    }
    /**
     * 2022-12-18 普通键值对数组的返回方式封装
     * @param type $lists       列表
     * @param type $fieldId     key
     * @param type $tableField  value
     */
    public static function dataResArr($lists, $fieldId, $tableField){
        $dataArr = [];
        foreach($lists as &$value){
            $dataArr[$value[$fieldId]] = $value[$tableField];
        }
        return $dataArr;
    }
    /**
     * 2023-01-16：动态数据列表拼装
     * @param type $dataArr
     * @param type $dynDatas
     * 'user_id'    =>'table_name=w_user&key=id&value=username'
     * 'goods_id'   =>'table_name=w_goods&key=id&value=goods_name'
     */
    public static function dynDataList($dataArr, $dynDatas){
        $optionArr = [];
        foreach($dynDatas as $fieldName=>$optionStr){
            $option     = equalsToKeyValue($optionStr);
            $tableName  = Arrays::value($option, 'table_name');
            $tableKey   = Arrays::value($option, 'key');
            $value      = Arrays::value($option, 'value');

            $optionArr[$fieldName] = self::dynData($dataArr, $fieldName, $tableName, $tableKey, $value);
        }
        return $optionArr;
    }
    /**
     * 单个动态数据
     * @param type $dataArr     二维数组
     * @param type $fieldName   字段名
     * @param type $tableName   表名
     * @param type $tableKey    key字段
     * @param type $value       值字段
     * @return type
     */
    public static function dynData($dataArr, $fieldName, $tableName,$tableKey, $value){
        $dataIds        = array_unique(array_column($dataArr, $fieldName));
        //20220131增加判断
        if(!$dataIds){
            //20220317增加判空
            return [];
        } else {
            // 例如：['id','=',$accountIds];
// 20230513：使用封装方法替代
//            $cond = [];
//            $cond[]        = [$tableKey,'in',$dataIds];
//            $res = Db::table($tableName)->where($cond)->cache(1)->column($value,$tableKey);

            $res = self::columnSearchByService($tableName, $tableKey, $value, $dataIds);
            return $res;
        }
    }
    
    /**
     * 
     * 由 dynSearchByDb 演化而来
     * @param type $tableName   数据表名
     * @param type $keyField    key字段
     * @param type $valueField  值字段
     * @param type $dataIds     数据id
     * @param type $cond        其他条件
     * @return type
     */
    protected static function columnSearchByService($tableName, $keyField, $valueField, $dataIds, $cond = [] ){
        $service        = DbOperate::getService($tableName);
        $cond[]         = [$keyField,'in',$dataIds];
        // 2022-12-18：静态查询
        if(method_exists($service, 'staticConList')){
            $lists   = $service::staticConList($cond);
            $res = array_column($lists, $valueField, $keyField);
        } else {
            // 20220523，增加了过滤条件
            $res = Db::table($tableName)->where($cond)->cache(1)->column($valueField,$keyField);
        }
        return $res;
    }
    
}

