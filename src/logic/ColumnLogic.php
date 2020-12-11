<?php
namespace xjryanse\system\logic;

use Exception;
use think\Db;
use xjryanse\system\service\SystemColumnService;
use xjryanse\system\service\SystemColumnBtnService;
use xjryanse\system\service\SystemColumnListService;
use xjryanse\system\service\SystemColumnOperateService;
use xjryanse\system\service\SystemColumnBlockService;
use xjryanse\logic\DbOperate;
/**
 * 字段逻辑
 */
class ColumnLogic
{
    use \xjryanse\traits\TreeTrait;
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
    public static function tableNameColumn( $tableName,$fields='' )
    {
        $con[]  = ['table_name','=',$tableName]     ;
        $info   = SystemColumnService::find( $con ) ;
        $info2  = self::getDetail( $info,$fields )  ;
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
    public static function info( $id )
    {
        $info   = SystemColumnService::getInstance($id)->get();
        return self::getDetail($info);      
    }
    /*
     * 取详细信息
     */
    private static function getDetail( $info, $fields='')
    {
        if(!$info){
            return false;
        }
        //是否只取某些字段
        $conField = [];
        if($fields){
            if(!is_array($fields)){
                $fields = explode(',', $fields);
            }
            $conField[] = ['name', 'in' , $fields ];
        }
        //字段列
        $con1[] = ['column_id','=',$info['id']];
        $con1[] = ['status','=',1];

        $info['listInfo']       = SystemColumnListService::lists( $conField ? array_merge( $conField, $con1 ) : $con1 );
        //按钮
        $info['btnInfo']        = SystemColumnBtnService::lists( $con1 );
        //操作
        $info['operateInfo']    = SystemColumnOperateService::lists( $con1 );
        //页面板块布局
        $info['blockInfo']      = SystemColumnBlockService::listsInfo( $con1 );        

        return $info;
    }

    //字段转换
    private static function scolumnCov( &$res ){
        if(!isset( $res['listInfo'] )){
            return $res;
        }
        //字段
        foreach($res['listInfo'] as $k=>&$v){
//            $v['option'] = SystemColumnListService::optionCov( $v['type'], $v['option'] );
            //冗余字段，方便前端使用
            $v['table_name'] = $res['table_name'] ;
            //选项
            $v['option'] = SystemColumnListService::getOption( $v['type'], $v['option'] );

            //联表数据
            if( $v['type'] == 'union' ){
                //参数
                $v['table_info'] = self::tableNameColumn( $v['option']['table_name'] ,isset($v['option']['fields']) ? $v['option']['fields'] : []);
            }
        }
        //按钮
        foreach($res['btnInfo'] as $k=>&$v){
            $v = SystemColumnBtnService::btnCov( $v );
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
    
    /**
     * 表名，查询条件
     * @param type $tableName
     * @param type $con
     */
    public static function dynamicColumn( $tableName ,$field, $key ,$con = [])
    {
        //替换资源链接
        $list = Db::table( $tableName )->where( $con )->column( $field, $key );
        return $list;
    }

}
