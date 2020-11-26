<?php
namespace xjryanse\system\service\columnlist;

use xjryanse\logic\DbOperate;
use think\Db;

abstract class Base
{
    /**
     * 表名，查询条件
     * @param type $tableName
     * @param type $con
     */
    protected static function dynamicColumn( $tableName ,$field, $key ,$con = [])
    {
        //替换资源链接
        $list = Db::table( $tableName )->where( $con )->column( $field, $key );
        return $list;
    }
    
    /**
     * 表名，查询条件
     * @param type $tableName
     * @param type $con
     */
    public static function dynamicLists( $tableName ,$con = [])
    {
        $service = DbOperate::getService( $tableName );
        $list = $service::lists( $con );
        return $list;
    }
}
