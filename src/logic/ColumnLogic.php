<?php
namespace xjryanse\system\logic;

use Exception;
use think\Db;
use xjryanse\logic\Arrays;
use xjryanse\system\service\SystemColumnService;
use xjryanse\system\service\SystemColumnBtnService;
use xjryanse\system\service\SystemColumnListService;
use xjryanse\system\service\SystemColumnOperateService;
use xjryanse\system\service\SystemColumnBlockService;
/**
 * 字段逻辑
 */
class ColumnLogic
{
    /**
     * 取默认表
     * @param type $controller  控制器
     * @param type $tableKey    表键
     * @param type $companyId   公司id
     * @return type
     */
    public static function defaultColumn(  $controller, $tableKey , $companyId = '')
    {
        $con[] = ['controller','=',$controller];
        $con[] = ['table_key','=',$tableKey];
        if($companyId){
            $con[] = ['company_id','in',['',$companyId]];
        }
        $info   = SystemColumnService::find($con);
        $info2  = self::getDetail($info);
        //循环
        $info2['color_con'] = $info2['color_con'] ? json_decode( $info2['color_con'],true ) : [];
        //字段转换
        return self::scolumnCov($info2);
    }
    /**
     * 获取搜索字段
     */
    public static function getSearchFields( $columnInfo )
    {
        $searchFields = [];
        foreach($columnInfo['listInfo'] as $v) {
            if($v['search_type'] >=0){
                $searchFields[$v['search_type']][] = $v['name'];
            }
        }
        return $searchFields;
    }
    
    /**
     * 传一个表名，拿到默认的column信息。尽量不使用
     */
    public static function tableNameColumn( $param )
    {
        $con[]  = ['table_name','=',Arrays::value( $param, 'table_name')];
        $info   = SystemColumnService::find($con);
        $info2  = self::getDetail($info);   
        //字段转换
        return self::scolumnCov($info2);
    }
    
    public static function tableHasRecord( $tableName )
    {
        $con[]      = ['table_name','=',$tableName];
        $info       = SystemColumnService::find($con);
        return $info;
    }
    /**
     * 信息
     */
    public static function info( $param )
    {
        $id     = Arrays::value( $param, 'id');
        $info   = SystemColumnService::getInstance($id)->get();
        return self::getDetail($info);      
    }
    /*
     * 取详细信息
     */
    private static function getDetail( $info )
    {
        if(!$info){
            return false;
        }
        //字段列
        $con1[] = ['column_id','=',$info['id']];
        $con1[] = ['status','=',1];
        $info['listInfo']       = SystemColumnListService::lists( $con1 );
        //按钮
        $con2[] = ['column_id','=',$info['id']];
        $con2[] = ['status','=',1];
        $info['btnInfo']        = SystemColumnBtnService::lists( $con2 );
        //操作
        $con3[] = ['column_id','=',$info['id']];
        $con3[] = ['status','=',1];
        $info['operateInfo']    = SystemColumnOperateService::lists( $con3 );        
        //页面板块布局
        $con4[] = ['column_id','=',$info['id']];
        $con4[] = ['status','=',1];
        $info['blockInfo']    = SystemColumnBlockService::listsInfo( $con4 );        
        
        return $info;
    }
    
    //字段转换
    private static function scolumnCov( &$res ){
        if(isset( $res['listInfo'] )){
            //字段
            foreach($res['listInfo'] as $k=>&$v){
                $v['option'] = SystemColumnListService::optionCov( $v['type'], $v['option'] );
            }
            //按钮
            foreach($res['btnInfo'] as $k=>&$v){
                $v = SystemColumnBtnService::btnCov( $v );
            }
        }
        return $res;
    }
    /**
     * 生成表信息
     */
    public static function generate( $table )
    {
        if( self::tableHasRecord( $table ) ){
            throw new Exception('数据表已存在，不支持重复生成');
        }
        //取数据表字段
        $columns    = Db::table('information_schema.columns')->field('column_name')->where('table_name',$table)->column('column_name');
        if(!$columns){
            throw new Exception('数据表不存在，或没有字段，不能生成');
        }
        //总表
        $data['table_name'] = $table;
        $res = SystemColumnService::save( $data );
        //字段
        $tmp = [];
        foreach($columns as $k=>$v){
            $tmp[$k]['column_id']   = $res['id'];
            $tmp[$k]['name']        = $v;
            $tmp[$k]['label']       = $v;
            $tmp[$k]['type']    = ($v == 'id') ? 0 :'text';   //id隐藏域
            $tmp[$k]['is_add']  = (in_array($v, ['id','create_time','update_time'])) ? 0 :1;   
            $tmp[$k]['is_edit'] = (in_array($v, ['create_time','update_time'])) ? 0 :1;
            //TODO优化
            SystemColumnListService::save( $tmp[$k] );
        }

        //保存默认的操作信息
        $operateKeys    = ['add'=>'添加','edit'=>'编辑','delete'=>'删除','copy'=>'复制','export'=>'导出','import'=>'导入'];
        foreach( $operateKeys as $k=>$v){
            $tmpp = [];
            $tmpp['column_id']      = $res['id'];
            $tmpp['operate_key']    = $k;
            $tmpp['operate_name']   = $v;
            SystemColumnOperateService::save( $tmpp );
        }
        return $res;
    }

}
