<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 数据表
 */
class SystemColumnService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemColumn';
    /**
     * 表名取id
     */
    public static function tableNameGetId( $tableName )
    {
        $con[]  = ['table_name','=',$tableName];
        $info   = self::find( $con ) ;
        return $info ? $info['id'] : '';
    }
}
