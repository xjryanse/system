<?php
namespace xjryanse\system\service\columnlist;
use xjryanse\system\interfaces\ColumnListInterface;
use think\Db;
use xjryanse\logic\Arrays;
/**
 * 模板设定
 */
class Tplset extends Base implements ColumnListInterface
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
        return isset($data[$option['name']]) ? $data[$option['name']] : (new \StdClass()) ;
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
     * 模板分组设定值，适用于多维度的设置（如价格）
     * @param type $tplTable        模板表
     * @param type $tplMainKey      主key
     * @param type $tplGroupKey     分组key
     * @param type $tplDataKey      数据key
     * @param type $mainTable       价格主表
     * @param type $mainDataKey     数据key
     * @param type $tplCond         模板表条件
     * @param type $mainTableCond   价格主表条件
     * @param type $preData         预赋值数据
     * @param type $matchesKeys     数据继承keys
     * @return array
     */
    public static function templateGroupSet( $tplTable, $tplMainKey, $tplGroupKey,$tplDataKey, $mainTable ,$mainDataKey, $tplCond = [] , $mainTableCond=[], $preData=[] ,$matchesKeys=[])
    {
        //模板表数据
        $tpls   = Db::table( $tplTable )->where( $tplCond )->select( );
        //数据主表数据
        $lists  = Db::table( $mainTable )->where( $mainTableCond )->column('*',$mainDataKey );
        //分组key：平台/卖家
        $groupKeys = Db::table( $tplTable ) ->where( $tplCond )->column( 'distinct '.$tplGroupKey );
        //模板表主key
        $mainKeys = Db::table( $tplTable ) ->where(  $tplCond )->column( 'distinct '.$tplMainKey );

        $dataArr = [];
        foreach( $mainKeys as $v ){
            $tmpData['keyName'] = $v;
            foreach( $groupKeys as $v2 ){
                //拿分组当key
                $ddata = [];
                //匹配key值
                foreach( $tpls as $key =>$value ){
                    if( $value[ $tplMainKey ] == $v && $value[ $tplGroupKey ] == $v2 ){
                        //模板数据key匹配，则取该数据
                        if(isset($lists[ $value[ $tplDataKey ] ])){
                            //匹配到，取元数据
                            $ddata = $lists[ $value[ $tplDataKey ] ];
                        } else {
                            //未匹配到，赋个空数据
                            $ddata = array_merge(Arrays::keyReplace($value, $matchesKeys),$preData,[$mainDataKey=>$value[ $tplDataKey ]]);
                        }
                    }
                }
                $tmpData[ $v2 ] = $ddata;
            }
            $tmpData[ 'total' ] = [];
            $dataArr[] = $tmpData;
        }

        return $dataArr;
    }

}

