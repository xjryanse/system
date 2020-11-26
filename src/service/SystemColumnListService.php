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
        $class = '\\xjryanse\\system\\service\\columnlist\\'. ucfirst( $type );
        return class_exists( $class ) ? $class::getOption( $optionStr ) : '' ;
    }
    
}
