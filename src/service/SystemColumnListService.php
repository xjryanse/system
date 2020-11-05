<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use think\Db;

/**
 * 字段明细
 */
class SystemColumnListService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemColumnList';
    /**
     * 选项转换
     * @param type $type
     */
    public static function optionCov( $type, $optionStr )
    {
        //枚举项
        if($type == 'enum'){
            return  $optionStr ? json_decode( $optionStr,true ) : [];
        }
        //动态枚举项        //联表数据      二级复选，一级复选,动态树
        if(in_array($type,[ FR_COL_TYPE_DYNENUM ,'union','subcheck', FR_COL_TYPE_CHECK,FR_COL_TYPE_DYNTREE])){
            $arr            = equalsToKeyValue( $optionStr , '&');
            return $arr;
        }
        return $optionStr;
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
     * @return array
     */
    public function templateGroupSet( $tplTable, $tplMainKey, $tplGroupKey,$tplDataKey, $mainTable ,$mainDataKey, $tplCond = [] , $mainTableCond=[], $preData=[] )
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
                            $ddata = array_merge($preData,[$mainDataKey=>$value[ $tplDataKey ]]);
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
