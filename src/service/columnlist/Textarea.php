<?php
namespace xjryanse\system\service\columnlist;

use xjryanse\system\interfaces\ColumnListInterface;
use xjryanse\system\service\SystemCateService;
use xjryanse\logic\DataCheck;
/**
 * 枚举
 */
class Textarea extends Base implements ColumnListInterface
{
    /**
     * 获取option
     * @param type $optionStr
     */
    public static function getOption( $optionStr,$data=[])
    {
        //非json格式，表示为key优先从cate表中提取
        if(!DataCheck::isJson($optionStr)){
            $resArr = SystemCateService::columnByGroup( $optionStr );
            return $resArr ? : [];
        }
        return  $optionStr ? json_decode( $optionStr,true ) : [];
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

