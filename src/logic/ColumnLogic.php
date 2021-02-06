<?php
namespace xjryanse\system\logic;

use Exception;
use think\Db;
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
    use \xjryanse\traits\TreeTrait;
    use \xjryanse\traits\DebugTrait;
    /**
     * 取默认表
     * @param type $controller  控制器
     * @param type $tableKey    表键
     * @param type $companyId   公司id
     * @param type $cateFieldValue  可以是字符串或数组，如数组，按key取值
     * @return type
     */
    public static function defaultColumn(  $controller, $tableKey , $companyId = '',$cateFieldValue='',$methodKey ='')
    {
        $con[] = ['controller','=',$controller];
        $con[] = ['table_key','=',$tableKey];
        if($companyId){
            $con[] = ['company_id','in',['',$companyId]];
        }
        $columnId     = SystemColumnService::mainModel()->where($con)->value('id');
        return self::getById($columnId,[], $cateFieldValue, $methodKey );
    }
    /**
     * 获取搜索字段
     */
    public static function getSearchFields( $columnInfo ,$queryType = 'where')
    {
        $searchFields = [];
        foreach($columnInfo['listInfo'] as $v) {
            if($v['search_type'] >=0 && $v['query_type'] == $queryType ){
                $searchFields[$v['search_type']][] = $v['name'];
            }
        }
        return $searchFields;
    }
    /**
     * 获取搜索字段
     */
    public static function getImportFields( $columnInfo )
    {
        $importFields = [];
        foreach($columnInfo['listInfo'] as $v) {
            $importFields[$v['label']] = $v['name'];
        }
        return $importFields;
    }    
    
    /**
     * 传一个表名，拿到默认的column信息
     * @param type $tableName   表名
     * @param type $fields      表字段
     * @param type $methodKey   方法key 
     * @param type $data        用于联动过滤的数据
     * @return type
     */
    public static function tableNameColumn( $tableName,$fields='' ,$methodKey = '',$data=[])
    {
        $con[]  = ['table_name','=',$tableName]     ;
        $columnId     = SystemColumnService::mainModel()->where($con)->value('id');
        return self::getById($columnId,$fields,'',$methodKey,$data);
//        没测20201228
//        $info   = SystemColumnService::find( $con ) ;
//        $info2  = self::getDetail( $info,$fields )  ;
//        //字段转换
//        return self::scolumnCov($info2);
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
     * @param type $info        表信息
     * @param type $fields      字段数组
     * @param type $conField    字段过滤信息
     * @param type $methodKey    方法id
     * @return boolean
     */
    private static function getDetail( $info, $fields='',$conField = [],$methodKey = '')
    {
        if(!$info){
            return false;
        }
        //是否只取某些字段
        if($fields){
            if(!is_array($fields)){
                $fields = explode(',', $fields);
            }
            $conField[] = ['name', 'in' , $fields ];
        }
        //字段列
        $con1[] = ['column_id','=',$info['id']];
        $con1[] = ['status','=',1];

        self::debug('查询条件',array_merge($con1,[['method_key','=',$methodKey]]));
//        //方法id存在，且有自定义值
//        dump( array_merge($con1,[['method_key','=',$methodKey]]) );
//        dump( SystemColumnListService::count(array_merge($con1,[['method_key','=',$methodKey]])) );
        if($methodKey && $methodKey !="*" && SystemColumnListService::count(array_merge($con1,[['method_key','=',$methodKey]]))){
            $con2[] = ['method_key', '=', $methodKey ];
        } else {
            $con2[] = ['method_key', '=', ''];   //没数据默认查空
        }

        $info['listInfo']       = SystemColumnListService::lists( $conField ? array_merge( $conField, $con1,$con2 ) : array_merge( $con1,$con2 ) );
        //按钮
        $info['btnInfo']        = SystemColumnBtnService::lists( $con1 );
        //操作
        $info['operateInfo']    = SystemColumnOperateService::lists( $con1 );
        //页面板块布局
        $info['blockInfo']      = SystemColumnBlockService::listsInfo( $con1 );
        //新增和编辑页面的flex布局
        $info['flexInfo']       = isset($info['flex_id']) && $info['flex_id'] ? FlexLogic::getFlex( $info['flex_id'] ) : [];

        return $info;
    }

    //字段转换
    /**
     * 
     * @param type $res
     * @param type $data    用于联动的数据
     * @return type
     */
    private static function scolumnCov( &$res,$data=[] ){
        if(!isset( $res['listInfo'] )){
            return $res;
        }
        //字段
        foreach($res['listInfo'] as $k=>&$v){
//            $v['option'] = SystemColumnListService::optionCov( $v['type'], $v['option'] );
            //冗余字段，方便前端使用
            $v['table_name'] = $res['table_name'] ;
            //选项
            $v['option'] = SystemColumnListService::getOption( $v['type'], $v['option'] ,$data);
            //查询条件
            $v['show_condition'] = json_decode($v['show_condition'],JSON_UNESCAPED_UNICODE);

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
        $tableArr = explode('_', $table);
        $data['controller'] = $tableArr[1];
        unset($tableArr[0]);
        unset($tableArr[1]);
        $data['table_key']  = camelize(implode('_',$tableArr));
        $data['table_name'] = $table;
        $res = SystemColumnService::save( $data );
        //字段
        $tmp = [];
        $sort = 100;    //字段排序值
        foreach($columns as $k=>$v){
            $tmp[$k]['column_id']   = $res['id'];
            $tmp[$k]['name']        = $v;
            $tmp[$k]['label']       = $v;
            $tmp[$k]['sort']        = $sort;    $sort += 100;   //排序
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
    /**
     * 最新20201228
     * @param type $columnId        字段id
     * @param type $fields          指定字段
     * @param type $cateFieldValue  分类值（用于不同表取不同的字段）
     * @param type $methodKey        方法id
     * @return type
     */
    public static function getById( $columnId ,$fields = [], $cateFieldValue='',$methodKey = '',$data=[])
    {
        $info   = SystemColumnService::getInstance( $columnId )->get();
        $con    = [];
        //分类取值
        if($info['cate_field_name'] && is_array($cateFieldValue)){
            $cateFieldValue = isset($cateFieldValue[$info['cate_field_name']]) ? $cateFieldValue[$info['cate_field_name']] : '';
        }
        //分类名
        if($info['cate_field_name'] && $cateFieldValue){
            //按分类名进行过滤
            $con[] = [ 'cate_field_value','in',[ $cateFieldValue, '' ]];
        }
        $info2  = self::getDetail( $info, $fields, $con ,$methodKey);
        //循环
        $info2['color_con'] = $info2['color_con'] ? json_decode( $info2['color_con'],true ) : [];
        //字段转换
        return self::scolumnCov($info2,$data);
    }
    /**
     * 导入数据转换
     */
    public static function getCovData( $columnInfo )
    {
        $covFields = [];
        foreach($columnInfo['listInfo'] as $v) {
            //TODO优化
            if($v['type'] == 'enum'){
                $covFields[$v['name']] = array_flip($v['option']);
            }
        }
        return $covFields;
    }
    
}
