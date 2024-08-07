<?php
namespace xjryanse\system\service\columnlist;

use xjryanse\system\service\SystemCompanyService;
use xjryanse\logic\DbOperate;
use xjryanse\logic\Arrays;
use think\Db;

abstract class Base
{
    /**
     * 表名，查询条件
     * @param type $tableName
     * @param type $con
     */
    protected static function dynamicColumn( $tableName ,$field, $key ,$con = [], $orderBy="" ,$cache=false)
    {
        $service = DbOperate::getService( $tableName );
        if( $service::mainModel()->hasField('company_id') ){
            $con[] = ['company_id','=',session(SESSION_COMPANY_ID) ];
        }
        //替换资源链接
        $inst = Db::table( $tableName )->where( $con );
        if($orderBy){
            $inst->order($orderBy);
        }
        //缓存
        if($cache){
            $inst->cache(86400);
        } else {
            $inst->cache(5);
        }
        //查询
        $list = $inst->column( $field, $key );
        return $list;
    }

    /**
     * 表名，查询条件
     * @param type $tableName
     * @param type $con
     */
    public static function dynamicLists( $tableName ,$con = [],$orderBy = '')
    {
        $service = DbOperate::getService( $tableName );
        $list = $service::lists( $con , $orderBy);
        $arr = $list ? $list->toArray() : [];
        // 20231207:增加类型和版本控制
        if($service::mainModel()->hasField('comp_cate')){
            // 20231208:封装
            $tmpArr = $service::comCateLevelListArr();
            $arr = array_merge($arr, $tmpArr);
        }
        
        return $arr;
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
        if($dataArr && (is_array($dataArr) || is_object($dataArr)) ){
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
}
