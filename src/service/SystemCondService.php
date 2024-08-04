<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Debug;
use xjryanse\logic\DbOperate;
use xjryanse\logic\Arrays;
use xjryanse\logic\Arrays2d;
use Exception;
/**
 * 达成条件
 */
class SystemCondService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemCond';

    /**
     * itemKey获取达成条件
     * @param type $itemType
     * @param type $itemKey
     * @param type $param
     * @return type
     */
    public static function listsByItemKey($itemType, $itemKey, $param = []) {
        $con[] = ['item_type', 'in', $itemType];
        $con[] = ['item_key', 'in', $itemKey];
        $con[] = ['status', '=', 1];
        // Debug::debug('listsByItemKey查询条件', $con);
        return self::getCond($con, $param);
    }
    
    protected static function getCond($con, $param) {
        //20220814优化
        $listsRaw = self::staticConList($con);
        $lists = json_encode($listsRaw, JSON_UNESCAPED_UNICODE);
        //20210625不缓存
        // $lists = json_encode( self::lists($con, 'group_id','*',86400) ,JSON_UNESCAPED_UNICODE);
//        Debug::debug('$param测', $param);
//        Debug::debug('查询结果Sql-1', $lists);
        if ($param) {
            Debug::debug('if($param)中打印', $param);
            foreach ($param as $key => $value) {
//                Debug::debug('$key', $key);
//                Debug::debug('$value', $value);
                if(is_array($value) || is_object($value)){
                    continue;
                }
                $lists = str_replace('{$' . $key . '}', $value, $lists);
            }
//            Debug::debug('$lists', $lists);
//            Debug::debug('-------------------', '');
        }
        // Debug::debug('查询结果Sql-2', $lists);
        $listsRes = json_decode($lists, true);
        // Debug::debug('查询结果Sql-3', $listsRes);
        foreach ($listsRes as $key => &$value) {
            $value['judge_cond'] = json_decode($value['judge_cond'], JSON_UNESCAPED_UNICODE);
        }
        // Debug::debug('查询结果Sql-4', $listsRes);

        return $listsRes;
    }
    /**
     * 20240803:多个key,需同时满足才算过：
     * 用于流程的预提取下一节点（当前节点未批，流程未过，但是要提取下一节点来前端展示）
     * @param type $itemType
     * @param type $itemKeys
     * @param type $dataId
     * @param type $param
     * @return type
     */
    public static function isReachByItemKeyMulti($itemType, $itemKeys, $dataId, $param = []) {
        foreach($itemKeys as $itemKey){
            // 一个没过就是没过
            if($itemKey && !self::isReachByItemKey($itemType, $itemKey,$dataId, $param)){
                return false;
            }
        }
        return true;
    }
    /**
     * 根据itemKey，判断条件是否达成
     * @param type $itemType
     * @param type $itemKey
     * @param type $dataId
     * @param type $param
     * @return bool
     */
    public static function isReachByItemKey($itemType, $itemKey, $dataId, $param = []) {
        //条件
        $conditions = self::listsByItemKey($itemType, $itemKey, $param);

        $results = self::conditionsGetResult($conditions, $dataId);
        return $results;
    }
    
    private static function conditionsGetResult($conditions, $dataId) {
        if (!$conditions) {
            return false;
        }
        // dump($conditions);
        $res = [];
        foreach ($conditions as &$v) {
            $result = self::conditionResult($v, $dataId);
            // Debug::dump($v);
            // Debug::dump($result);
            if(!$result && Arrays::value($v, 'false_throw_msg')){
                throw new Exception($v['false_throw_msg']);
            }
            //结果
            $res[$v['group_id']][] = $result;
        }

        foreach ($res as $value) {
            //某一组全为true（没有false）,说明条件达成，
            if (!in_array(false, $value)) {
                return true;
            }
        }
        return false;
        
    }
    
    
    /**
     * 单条件取结果
     * @param type $condition   条件
     * @param type $dataId       入参
     */
    private static function conditionResult($condition, $dataId) {
        $dataType   = Arrays::value($condition, 'data_type');
        $dataField  = Arrays::value($condition, 'data_field');
        $judgeTable = Arrays::value($condition, 'judge_table');
        $judgeCond  = Arrays::value($condition, 'judge_cond', []);
        $judgeType  = Arrays::value($condition, 'judge_type');
        $judgeField = Arrays::value($condition, 'judge_field');
        //结果
        $judgeSign  = Arrays::value($condition, 'judge_sign');
        $judgeValue = Arrays::value($condition, 'judge_value');
        
        // 获取用于判断的二维数组
        $dataArr = self::getDataArr($judgeTable, $dataId, $dataType, $dataField);
        // Debug::dump($dataArr);
        // 【核心】数据过滤
        $dataFilter = Arrays2d::listFilter($dataArr, $judgeCond);
        //dump($condition);
        //dump($dataFilter);
        // 过滤结果处理
        $result = self::dataCalValue($dataFilter, $judgeType, $judgeField, $judgeSign, $judgeValue);
        return $result;
    }
    /**
     * 
     * @param type $judgeTable
     * @param type $dataId
     * @param type $dataType
     * @param type $dataField
     */
    private static function getDataArr($judgeTable, $dataId, $dataType, $dataField = ''){
        $service = DbOperate::getService($judgeTable);
        if(!$service){
            throw new Exception('条件数据表未配置'.$judgeTable);
        }
        // 20240802:改info
        $data       = $service::getInstance($dataId)->info();
        // 数据
        if($dataType == 'data'){
            $dataArr    = [$data];
        }
        // 属性
        if($dataType == 'attr'){
            $dataArr    = $service::getInstance($dataId)->objAttrsList($dataField);
        }
        // 20240802子数据
        if($dataType == 'subArray'){
            $dataArr    = $data[$dataField];
        }
        // 20240802子数据
        if($dataType == 'subData'){
            $dataArr    = [$data[$dataField]];
        }
        // Debug::dump($data);
        return $dataArr;
    }
    /**
     * 根据数据计算结果
     * @param type $dataArr
     */
    private static function dataCalValue($dataArr, $judgeType, $judgeField, $judgeSign, $judgeValue){
        $resValue = 0;
        if($judgeType == 'count'){
            $resValue = count($dataArr);
        }
        if($judgeType == 'sum'){
            if(!$judgeField){
                throw new Exception('judge_field未配置');
            }
            $resValue = Arrays2d::sum($dataArr, $judgeField);
        }
        
        $signReplace['=']   = '==';   // 等号'
        $signReplace['<>']  = '!=';  //不等号'
        $sign = Arrays::value($signReplace, $judgeSign, $judgeSign);
        $code = 'return \'' . $resValue . '\' ' . $sign . ' ' . $judgeValue . ';';
        return eval($code);
    }
}
