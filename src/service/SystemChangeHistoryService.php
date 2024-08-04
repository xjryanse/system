<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays2d;

/**
 * 变更历史表
 * 可以恢复某个时段的配置数据
 */
class SystemChangeHistoryService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;


    // 静态模型：配置式数据表
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemChangeHistory';

    /*
     * 复现历史数据
     * @param type $table   数据表
     * @param type $nowArr  当前数据
     */
    public static function recoverHisDataArr($table, $nowArr, $time){
        $con    = [];
        $con[]  = ['belong_table','=',$table];
        $con[]  = ['change_time','>=',$time];
        $lists      = self::where($con)->order('change_time desc')->select();
        $listsArr   = $lists ? $lists->toArray() : [];

        foreach($nowArr as &$v){
            $cone       = [];
            $cone[]     = ['belong_table_id','=',$v['id']];
            $changes    =  Arrays2d::listFilter($listsArr, $cone);
            // 循环恢复旧数据
            foreach($changes as $ve){
                $oldData = json_decode($ve['previous_value'], JSON_UNESCAPED_UNICODE);
                // 旧数据替换
                $v = array_merge($v, $oldData);
            }
        }
        return $nowArr;
    }

}
