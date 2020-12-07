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
    /**
     * 关联表先删除后保存（角色，权限等）
     * @param type $tableName       关联表名
     * @param type $mainField       关联表主字段
     * @param type $toField         关联表写入字段
     * @param type $mainId          主id
     * @param type $dataArr         数据数组
     */
    protected static function midDeleteAndSave( $tableName, $mainField, $toField, $mainId, $dataArr)
    {
        $con1[] = [ $mainField,'=', $mainId];
        //先删再写
        $class = DbOperate::getService( $tableName );
        $class::mainModel()->where( $con1 )->delete();
        foreach( $dataArr as $vv ){
            //写资源
            $tmpData    = [];
            $tmpData[ $mainField ]    = $mainId ;
            $tmpData[ $toField ]      = $vv;
            //TODO优化为批量保存
            $class::save( $tmpData );
        }
    }
}
