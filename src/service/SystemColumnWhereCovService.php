<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Strings;
use xjryanse\logic\Arrays;
use xjryanse\system\service\columnlist\Dynenum;
use xjryanse\logic\Datetime;
use think\Db;

/**
 * 字段明细
 */
class SystemColumnWhereCovService implements MainModelInterface {

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
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemColumnWhereCov';

    /*
     * 获取where查询条件
     */

    public static function getWhere($columnId, $param = []) {
        $con[] = ['column_id', '=', $columnId];
        $list = self::staticConList($con);
        $resCon = [];
        $yearmonth = '';
        $date = '';
        foreach ($list as $v) {
            // 固定
            if ($v['type'] == 'fixed') {
                if (isset($param[$v['field']]) && $param[$v['field']] !== '' && $param[$v['field']] == $v['value']) {
                    $resCon = array_merge($resCon, json_decode($v['where_con'], JSON_UNESCAPED_UNICODE));
                }
            }
            // 动态联动搜索
            // table_name=w_circuit&key=id&value=circuit_name
            if ($v['type'] == 'dynenum' && Arrays::value($param, $v['field'])) {
                $options = Strings::equalsToKeyValue($v['option']);
                $tableName = Arrays::value($options, 'table_name');
                $key = Arrays::value($options, 'key');
                $value = Arrays::value($options, 'value');

                $res = self::getDynenumColumns($tableName, $key, $value, $param[$v['field']]);
                // 值固定用DYNVALUES
                $jsonStr = str_replace('DYNVALUES', json_encode($res), $v['where_con']);
                $resCon = array_merge($resCon, json_decode($jsonStr, JSON_UNESCAPED_UNICODE));
            }
            // 枚举项搜索
            if ($v['type'] == 'enum' && Arrays::value($param, $v['field'])) {
                $options = Strings::equalsToKeyValue($v['option']);
                $tableName = Arrays::value($options, 'table_name');
                $key = Arrays::value($options, 'key');
                $value = Arrays::value($options, 'value');

                $res = self::getDynenumColumns($tableName, $key, $value, $param[$v['field']]);
                // 值固定用DYNVALUES
                $jsonStr = str_replace('DYNVALUES', json_encode($res), $v['where_con']);
                $resCon = array_merge($resCon, json_decode($jsonStr, JSON_UNESCAPED_UNICODE));
            }
            // 时间的月份，需加日期使用
            if ($v['type'] == 'timeMonth' && Arrays::value($param, $v['field'])) {
                $yearmonth = Arrays::value($param, $v['field']);
            }
            // 时间的日期，需加月份使用
            if ($v['type'] == 'timeDay' && Arrays::value($param, $v['field'])) {
                $date = Arrays::value($param, $v['field']);
                if ($yearmonth && $date) {
                    $dateStr = $yearmonth . '-' . $date;

                    $startTime = Datetime::dateStartTime($dateStr);
                    $endTime = Datetime::dateEndTime($dateStr);
                    // 字符串替换
                    $jsonStrO = str_replace('STARTTIME', $startTime, $v['where_con']);
                    $jsonStr = str_replace('ENDTIME', $endTime, $jsonStrO);

                    $resCon = array_merge($resCon, json_decode($jsonStr, JSON_UNESCAPED_UNICODE));
                }
            }
        }


        return $resCon;
    }

    /**
     * 20230417 动态数组    
     * @param type $tableName   表名
     * @param type $key         键名
     * @param type $value       值名
     * @param type $fieldValue  搜索内容
     */
    public static function getDynenumColumns($tableName, $key, $value, $fieldValue) {
        $conD = [];
        $conD[] = [$value, 'like', '%' . $fieldValue . '%'];
        $res = Db::table($tableName)->where($conD)->column($key);
        return $res;
    }

    public static function getEnumColumns($tableName, $key, $value, $fieldValue) {
        $conD = [];
        $conD[] = [$value, '=', $fieldValue];
        $res = Db::table($tableName)->where($conD)->column($key);
        return $res;
    }

}
