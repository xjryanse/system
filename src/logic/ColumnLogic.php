<?php
namespace xjryanse\system\logic;

use xjryanse\logic\Arrays;
use xjryanse\system\service\SystemColumnService as ScolumnService;
use xjryanse\system\service\SystemColumnBtnService as ScolumnBtnService;
use xjryanse\system\service\SystemColumnListService as ScolumnListService;
use xjryanse\system\service\SystemColumnOperateService as ScolumnOperateService;
use xjryanse\system\service\SystemColumnBlockService as ScolumnBlockService;

/**
 * 字段逻辑
 */
class ColumnLogic
{
    //controller，table_key，取字段信息；getDefaultTable
    public function defaultColumn( $param )
    {
        $controller = Arrays::value( $param, 'controller'); 
        $tableKey   = Arrays::value( $param, 'table_key'); 
        $companyId  = Arrays::value( $param, 'company_id');

        $con[] = ['controller','=',$controller];
        $con[] = ['table_key','=',$tableKey];
        if($companyId){
            $con[] = ['company_id','in',['',$companyId]];
        }
        $info = ScolumnService::find($con);
        $info2 = $this->getDetail($info);      
        //循环
        $info2['color_con'] = $info2['color_con'] ? json_decode( $info2['color_con'],true ) : [];
        //字段转换
        return self::scolumnCov($info2);
    }
    /**
     * 传一个表名，拿到默认的column信息。尽量不使用
     */
    public function tableNameColumn( $param )
    {
        $con[]  = ['table_name','=',Arrays::value( $param, 'table_name')];
        $info   = ScolumnService::find($con);
        $info2  = $this->getDetail($info);   
        //字段转换
        return self::scolumnCov($info2);
    }
    
    public function tableHasRecord( $param )
    {
        $tableName  = Arrays::value( $param, 'tableName');
        $con[]      = ['table_name','=',$tableName];
        $info       = ScolumnService::find($con);
        return $info;
    }
    /**
     * 信息
     */
    public function info( $param )
    {
        $id     = Arrays::value( $param, 'id');
        $info   = ScolumnService::getInstance($id)->get();
        return $this->getDetail($info);      
    }
    /*
     * 取详细信息
     */
    private function getDetail( $info )
    {
        if(!$info){
            return false;
        }
        //字段列
        $con1[] = ['column_id','=',$info['id']];
        $con1[] = ['status','=',1];
        $info['listInfo']       = ScolumnListService::lists( $con1 );
        //按钮
        $con2[] = ['column_id','=',$info['id']];
        $con2[] = ['status','=',1];
        $info['btnInfo']        = ScolumnBtnService::lists( $con2 );
        //操作
        $con3[] = ['column_id','=',$info['id']];
        $con3[] = ['status','=',1];
        $info['operateInfo']    = ScolumnOperateService::lists( $con3 );        
        //页面板块布局
        $con4[] = ['column_id','=',$info['id']];
        $con4[] = ['status','=',1];
        $info['blockInfo']    = ScolumnBlockService::lists( $con4 );        
        
        return $info;
    }
    
    //字段转换
    private static function scolumnCov( &$res ){
        if(isset( $res['listInfo'] )){
            //字段
            foreach($res['listInfo'] as $k=>&$v){
                $v['option'] = ScolumnListService::optionCov( $v['type'], $v['option'] );
            }
            //按钮
            foreach($res['btnInfo'] as $k=>&$v){
                $v = ScolumnBtnService::btnCov( $v );
            }
        }
        return $res;
    }

}
