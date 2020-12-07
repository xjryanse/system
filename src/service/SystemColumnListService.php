<?php
namespace xjryanse\system\service;

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
     * @param type $type        类型
     * @param type $optionStr   选项字符串
     * @return type
     */
    public static function getOption( $type, $optionStr )
    {
        $class = self::getClassStr( $type );
        return class_exists( $class ) ? $class::getOption( $optionStr ) : '' ;
    }
    /**
     * 获取数据
     * @param type $type        类型
     * @param type $data        数据
     * @param type $columnInfo  字段信息
     * @return type
     */
    public static function getData( $type, $data, $columnInfo )
    {
        $class = self::getClassStr( $type );
        if( class_exists( $class )  ){
            return $class::getData( $data, $columnInfo  ) ;
        } else {
            return isset($data[$columnInfo['name']]) ? $data[$columnInfo['name']] : '';
        }
    }
    /**
     * 中间表保存数据
     */
    public static function saveData( $type, $data, $columnInfo )
    {
        $class = self::getClassStr( $type );
        if( class_exists( $class )  ){
            return $class::saveData( $data, $columnInfo  ) ;
        } else {
            return false;
        }
    }
    
    /**
     * 获取class
     * @param type $type
     */
    private static function getClassStr( $type )
    {
        return '\\xjryanse\\system\\service\\columnlist\\'. ucfirst( $type );
    }
}
