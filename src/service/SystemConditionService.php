<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use think\Db;

/**
 * 达成条件
 */
class SystemConditionService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\DebugTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemCondition';

    /*
     * itemid获取达成条件
     */
    public static function listsByItemId( $itemId ,$param = [])
    {
        $con[] = ['item_id','in',$itemId ];
        $lists =  self::lists($con,'group_id');
        foreach( $param as $key=>$value){
            $lists =  str_replace('{$'.$key.'}', $value, $lists );
        }
        return is_array($lists) ? $lists : json_decode( $lists,true);
    }
    /**
     * itemKey获取达成条件
     * @param type $itemType
     * @param type $itemKey
     * @param type $param
     * @return type
     */
    public static function listsByItemKey( $itemType, $itemKey ,$param = [])
    {
        $con[] = ['item_type','in',$itemType ];
        $con[] = ['item_key','in',$itemKey ];
        $lists =  self::lists($con,'group_id');
        foreach( $param as $key=>$value){
            $lists =  str_replace('{$'.$key.'}', $value, $lists );
        }
        return is_array($lists) ? $lists : json_decode( $lists,true);
    }
    /**
     * 根据itemId,判断条件是否达成
     */
    public static function isReachByItemId( $itemId, $param)
    {
        //条件
        $conditions = self::listsByItemId( $itemId,$param );
        $results    = self::conditionsGetResult($conditions);
        //相同group的数据，全部为true，则true;
        
        return $results;
    }
    /**
     * 根据itemKey，判断条件是否达成
     * @param type $itemType
     * @param type $itemKey
     * @param type $param
     */
    public static function isReachByItemKey( $itemType, $itemKey, $param )
    {
        //条件
        $conditions = self::listsByItemKey( $itemType, $itemKey ,$param );
        self::debug( __METHOD__.'$conditions', $conditions );
        $results    = self::conditionsGetResult($conditions);
        //相同group的数据，全部为true，则true;
        return $results;
    }
    /**
     * 条件取结果
     */
    private static function conditionsGetResult( $conditions )
    {
        //结果集
        $res = [];
        foreach( $conditions as &$v){
            $tmpResult      = Db::table( $v['judge_table'])->where( $v['judge_cond'] )->field( $v['judge_field'].' as result')->find();
            $v['resVal']    = $tmpResult['result'];
            $v['result']    = eval( "return ". $tmpResult['result'] . ' '. $v['judge_sign'] .' '. $v['judge_value'] .';' );
            $res[$v['group_id']][] = $v['result'];
        }
        foreach( $res as $value ){
            //某一组全为true（没有false）,说明条件达成，
            if(!in_array(false, $value)){
                return true;
            }
        }
        return false;
    }
}
