<?php
namespace app\scolumn\service;

use xjryanse\system\interfaces\MainModelInterface;

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
        //动态枚举项        //联表数据      二级复选，一级复选
        if(in_array($type,['dynenum','union','subcheck','check'])){
            $arr            = equalsToKeyValue( $optionStr , '&');
            return $arr;
        }
        return $optionStr;
    }
}
