<?php
namespace xjryanse\system\logic;

use Exception;
use think\Db;
use xjryanse\system\service\SystemColumnService;
//use xjryanse\system\service\SystemColumnBtnService;
use xjryanse\system\service\SystemColumnListService;
//use xjryanse\system\service\SystemColumnListGroupService;
//use xjryanse\system\service\SystemColumnOperateService;
//use xjryanse\system\service\SystemColumnBlockService;
//use xjryanse\user\service\UserAuthUserRoleService;
//use xjryanse\user\service\UserAuthRoleColumnService;
//use xjryanse\user\service\UserAuthRoleBtnService;
use xjryanse\logic\ModelQueryCon;
use xjryanse\logic\Debug;
use xjryanse\logic\DbOperate;
use xjryanse\logic\Cachex;
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
    public static function defaultColumn(  $controller, $tableKey , $companyId = '',$cateFieldValue='',$methodKey ='',$data = [])
    {
        $con[] = ['controller','=',$controller];
        $con[] = ['table_key','=',$tableKey];
//        if($companyId && SystemColumnService::mainModel()->hasField('company_id')){
//            $con[] = ['company_id','in',['',$companyId]];
//        }
        Debug::debug('字段查询条件',$con);
        $columnInfo = SystemColumnService::staticConFind($con);
        Debug::debug('字段查询$columnInfo',$columnInfo);
        $columnId = $columnInfo ? $columnInfo['id'] : "";
        
        return self::getById($columnId,[], $cateFieldValue, $methodKey ,$data);
    }
    /**
     * 获取搜索字段
     */
    public static function getSearchFields( $columnInfo ,$queryType = 'where')
    {
        $searchFields = [];
        if($columnInfo['listInfo']){
            foreach($columnInfo['listInfo'] as $v) {
                if($v['search_type'] >=0 && $v['query_type'] == $queryType ){
                    $searchFields[$v['search_type']][] = $v['name'];
                }
            }
        }
        return $searchFields;
    }
    /**
     * 将键值对参数，转拼接为mysql 模型类查询条件
     */
    public static function paramToQueryCon($tableName,$param){
        $columnInfo     = self::tableNameColumn($tableName);
        $whereFields    = self::getSearchFields($columnInfo);
        //【通用查询条件】
        $con        = ModelQueryCon::queryCon($param, $whereFields);
        return $con ;
    }
    /**
     * 可以放到表中查询的字段
     * @param type $columnId
     * @return type
     */
    public static function listFields( $columnId )
    {
        //【1】状态为开的字段
        $con[] = ['column_id','=',$columnId];
        $con[] = ['status','=',1];
        // $res = SystemColumnListService::mainModel()->where($con)->cache(86400)->column('distinct name');
        $nameArr = SystemColumnListService::staticConColumn('name');
        $res = array_unique($nameArr);
        //【2】数据表有的字段
        $tableName      = SystemColumnService::getInstance($columnId)->fTableName();
        $tableColumns   = DbOperate::columns($tableName);
        //数据表已有字段
        $tableFields    = array_column( $tableColumns,'Field');              
        //【3】取交集
        return array_intersect($res, $tableFields);
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
        $columnInfo = SystemColumnService::staticConFind($con);
        $columnId = $columnInfo ? $columnInfo['id'] : "";
        //$columnId     = SystemColumnService::mainModel()->where($con)->cache(86400)->value('id');
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
        $info       = SystemColumnService::find($con,86400);
        return $info;
    }
    /**
     * 信息
     */
    public static function info( $id )
    {
        //$info   = SystemColumnService::getInstance($id)->get();
        $info   = SystemColumnService::getInstance($id)->staticGet();
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
    private static function getDetail( $info, $fields='',$conField = [])
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

        //字段查询的字段【20210330】
        /*20220814优化
        $fieldStr = "id,column_id,method_key,label,name,type,query_type,option,search_type,search_show,"
                . "is_senior_search,is_list,is_list_edit,is_add,is_edit,only_detail,is_must,is_export,is_import_must,"
                . "is_span_company,is_linkage,list_style,list_pop,edit_btn_id,list_pop_operate,"
                . "min_width,width,form_col,cate_field_value,flex_item_id,show_condition";

        $listInfo = SystemColumnListService::lists( $conField ? array_merge( $conField, $con1 ) : $con1,"",$fieldStr,86400 );
        $info['listInfo']       = $listInfo ? $listInfo->toArray() : [];        
         */
        
        $info['listInfo']       = SystemColumnListService::staticConList($conField ? array_merge( $conField, $con1 ) : $con1);        

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
            //冗余字段，方便前端使用
            $v['table_name'] = $res['table_name'] ;
            //选项
            //数据中，取出与当前键名一致的id，用于动态枚举少量筛选数据
            $tempColumnData = $data ? : [];
            if(isset($data[$v['name']])){
                $ids = $data[$v['name']];
                $tempColumnData['id'] = $ids;
            }
            $v['option'] = SystemColumnListService::getOption( $v['type'], $v['option'] ,$tempColumnData);
            //查询条件
            $v['show_condition'] = json_decode($v['show_condition'],JSON_UNESCAPED_UNICODE);

            //联表数据
            if( $v['type'] == 'union' ){
                //参数
                $v['table_info'] = self::tableNameColumn( $v['option']['table_name'] ,isset($v['option']['fields']) ? $v['option']['fields'] : []);
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
        // $tableArr = explode('_', $table);
        /*
        $data['controller'] = $tableArr[1];
        unset($tableArr[0]);
        unset($tableArr[1]);
        $data['table_key']  = camelize(implode('_',$tableArr));
         */
        
        $data['controller'] = DbOperate::getController($table);
        $data['table_key']  = DbOperate::getTableKey($table);
        $data['table_name'] = $table;
        $res = SystemColumnService::save( $data );
        //字段
        $tmp = [];
        $sort       = 100;    //字段排序值
        $hideKeys   = ['sort',"create_time","update_time","status","has_used","is_lock","is_delete","creater","updater","app_id","company_id"];
        foreach($columns as $k=>$v){
            $tmp[$k]['column_id']   = $res['id'];
            $tmp[$k]['name']        = $v;
            $tmp[$k]['label']       = $v;
            $tmp[$k]['sort']        = $sort;    $sort += 100;   //排序
            $tmp[$k]['type']        = ($v == 'id') ? 'hidden' :'text';   //id隐藏域
            $tmp[$k]['is_add']      = (in_array($v, array_merge(['id'], $hideKeys))) ? 0 :1;   
            $tmp[$k]['is_edit']     = (in_array($v, $hideKeys)) ? 0 :1;
            $tmp[$k]['is_list']     = (in_array($v, array_merge(['id'], $hideKeys))) ? 0 :1;
            //TODO优化
            SystemColumnListService::save( $tmp[$k] );
        }
        /*
        //保存默认的操作信息
        $operateKeys    = ['add'=>'添加','edit'=>'编辑','delete'=>'删除','copy'=>'复制','export'=>'导出','import'=>'导入'];
        foreach( $operateKeys as $k=>$v){
            $tmpp = [];
            $tmpp['column_id']      = $res['id'];
            $tmpp['operate_key']    = $k;
            $tmpp['operate_name']   = $v;
            SystemColumnOperateService::save( $tmpp );
        }
         */
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
        $list = Db::table( $tableName )->where( $con )->cache(86400)->column( $field, $key );
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
        $key = Cachex::cacheKey($columnId ,$fields, $cateFieldValue,$methodKey,$data);
        //20220130，增加缓存
        return Cachex::funcGet('ColumnLogic_getById'.$key, function() use ($columnId ,$fields, $cateFieldValue,$methodKey,$data){
            //$info   = SystemColumnService::getInstance( $columnId )->get( 86400 );
            $info         = SystemColumnService::getInstance($columnId)->staticGet();
            Debug::debug('ColumnLogic::getById',$info);
            $con    = [];
            
            //分类取值
            if(isset($info['cate_field_name']) && $info['cate_field_name'] && is_array($cateFieldValue)){
                $cateFieldValue = isset($cateFieldValue[$info['cate_field_name']]) ? $cateFieldValue[$info['cate_field_name']] : '';
            }
            //分类名
            if(isset($info['cate_field_name']) && $info['cate_field_name'] && $cateFieldValue){
                //按分类名进行过滤
                $con[] = [ 'cate_field_value','in',[ $cateFieldValue, '' ]];
            }
            $info2  = self::getDetail( $info, $fields, $con);
            //循环
            // $info2['color_con'] = $info2['color_con'] ? json_decode( $info2['color_con'],true ) : [];
            //字段转换
            $res = self::scolumnCov($info2,$data);
            //带分组字段的数据列表
            //20220306新系统无需
            //$res['listInfoGroup'] = SystemColumnListGroupService::getColumnListInfo($columnId);      
            return $res;
        });
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
