<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;
use xjryanse\logic\DbOperate;
use think\Db;
/**
 * 异常数据比对
 */
class SystemDataCompareService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\ObjectAttrTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemDataCompare';

    
    use \xjryanse\system\service\dataCompare\DoTraits;

    /**
     * 
     * @param type $ids
     * @return type
     */
    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
            foreach ($lists as &$v) {
                $sql                = self::getSql($v);
                // 20240711:比对sql
                $v['compareSql']    = $sql;
                // 提取异常结果
                $resultArr          = self::calcResult($sql, $v);
                $v = array_merge($v, $resultArr);
            }
            // 20240713批量提交：is_error字段的更新
            DbOperate::dealGlobal();
            return $lists;
        });
    }
    
    protected static function getSql($data){
        $mainTable      = Arrays::value($data, 'main_table_name');
        $subTable       = Arrays::value($data, 'sub_table_name');
        
        $mainUniField   = Arrays::value($data, 'main_uni_field');
        $subUniField    = Arrays::value($data, 'sub_uni_field');
        // 子条件过滤
        $subCondFilter  = Arrays::value($data, 'sub_cond_filter');

        // 20240713:数据表不存在
        if(!DbOperate::isTableExist($mainTable) || !DbOperate::isTableExist($subTable)){
            return '';
        }
        
        $inst = Db::table($subTable)->alias('subTable')
                ->leftJoin($mainTable.' mainTable','subTable.'.$subUniField.'=mainTable.'.$mainUniField)
                ->where('mainTable.'.$mainUniField.' is null')
                ->where('subTable.'.$subUniField.' is not null')
                ->where('subTable.'.$subUniField.' <> \'\'')
                ->field('count(1) as `value`');
        if($subCondFilter){
            $inst->where('subTable.'.$subCondFilter);
        }
        $sql = $inst->buildSql();
        
        return $sql;        
    }
    
    protected static function calcResult($compareSql, $data = []){
        if(!$compareSql){
            return [];
        }
        // dump($data);
        // sql查询结果
        $result             = Db::query($compareSql);
        // 20240712：查询结果
        $v = [];
        $v['calcResult']    = $result ? $result[0]['value'] : '';
        // 有异常，无异常
        $v['hasErr']        = $v['calcResult'] ? 1 : 0;
        if($v['hasErr'] != Arrays::value($data, 'is_error')){
            // 更新
            $id = Arrays::value($data, 'id');
            self::getInstance($id)->updateRam(['is_error'=>$v['hasErr']]);
        }

        return $v;
    }

}
