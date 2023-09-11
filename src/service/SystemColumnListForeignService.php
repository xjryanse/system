<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\DbOperate;
use xjryanse\logic\Arrays;
use Exception;

/**
 * 外键
 */
class SystemColumnListForeignService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

// 静态模型：配置式数据表
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemColumnListForeign';
    //直接执行后续触发动作
    protected static $directAfter = true;

    public static function extraAfterSave(&$data, $uuid) {
        self::staticCacheClear();
    }

    public static function extraAfterUpdate(&$data, $uuid) {
        self::staticCacheClear();
    }

    /*
     * 2022-12-14：列表处理拼接统计数据
     * 一般用于extraDetails方法
     */

    public static function listAddStatics($tableName, array $dataLists) {
        $columnId = SystemColumnService::tableNameGetId($tableName);
        $con[] = ['column_id', '=', $columnId];
        $confLists = self::staticConList($con);
        if (!$confLists) {
            return $dataLists;
        }
        //【1】提取统计结果
        $staticsArr = [];
        foreach ($confLists as $v) {
            self::confCheck($v);
            $foTableName = SystemColumnService::getInstance($v['foreign_column_id'])->fTableName();
            if (!$foTableName) {
                throw new Exception($v['id'] . '的foreign_column_id找不到对应外键表，请联系开发');
            }
            $foService = DbOperate::getService($foTableName);
            if (!class_exists($foService)) {
                throw new Exception($v['id'] . '的外键类' . $foService . '不存在，请联系开发');
            }
            $keyField = $v['key_field'];
            $ids = array_column($dataLists, $keyField);
            // [1]count统计
            $foField = $v['foreign_key_field'];
            // 拼装查询条件
            $con = $v['condition'] ? json_decode($v['condition'], JSON_UNESCAPED_UNICODE) : [];
            // 【1.1】计数字段的名称（不能与数据库已有字段和求和字段重复）
            $countName = $v['count_name'];
            if ($countName && $v['is_count']) {
                $staticsArr[$countName] = $foService::groupBatchCount($foField, $ids, $con);
            }
            // 【1.2】求和字段的名称（不能与数据库已有字段和计数字段重复）
            $sumName = $v['sum_name'];
            if ($sumName && $v['is_sum']) {
                $staticsArr[$sumName] = $foService::groupBatchSum($foField, $ids, $v['foreign_sum_key'], $con);
            }
        }
        // 【2】拼接统计数据
        foreach ($dataLists as &$item) {
            foreach ($confLists as $v) {
                $countName = $v['count_name'];
                $countArr = Arrays::value($staticsArr, $countName, []);
                $sumName = $v['sum_name'];
                $sumArr = Arrays::value($staticsArr, $sumName, []);
                //统计结果的键名，一般是id；
                $ckField = Arrays::value($item, $v['key_field'], '');
                if ($countName) {
                    $item[$countName] = Arrays::value($countArr, $ckField, 0);
                }
                if ($sumName) {
                    $item[$sumName] = Arrays::value($sumArr, $ckField, 0);
                }
            }
        }

        return $dataLists;
    }

    /**
     * 配置校验
     */
    protected static function confCheck($foreignData) {
        // 外键表名
        if (!$foreignData['foreign_column_id']) {
            throw new Exception($foreignData['id'] . '的foreign_column_id未配置，请联系开发');
        }
        if (!$foreignData['foreign_key_field']) {
            throw new Exception($foreignData['id'] . '的foreign_key_field未配置，请联系开发');
        }
        if (!$foreignData['key_field']) {
            throw new Exception($foreignData['id'] . '的key_field未配置，请联系开发');
        }
        return true;
    }

}
