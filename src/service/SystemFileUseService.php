<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\DataCheck;
use think\Db;
use Exception;

/**
 * 文件使用记录
 */
class SystemFileUseService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemFileUse';

    public static function bind($fileId, $tableName, $recordId) {
        $data = self::bindSaveData($fileId, $tableName, $recordId);
        return self::save($data);
    }

    private static function bindSaveData($fileId, $tableName, $recordId, $data = []) {
        $data['file_id'] = $fileId;
        $data['use_table'] = $tableName;
        $data['use_table_id'] = $recordId;
        return $data;
    }

    /**
     * 20230430:从sql绑定数据
     * @param type $tableName
     * @param type $field
     * @return type
     */
    public static function tableSqlBind($tableName, $field) {
        $sql = "SELECT
                a.id,a." . $field . "
        FROM " . $tableName . " AS a
                LEFT JOIN w_system_file_use AS b ON a.id = b.use_table_id 
        WHERE
                b.use_table_id IS NULL and (a." . $field . " is not null and a." . $field . " <> '') limit 100";
        $data = Db::query($sql);
        if (!$data) {
            throw new Exception('没有需要绑定的记录');
        }
        $arr = [];
        foreach ($data as $v) {
            $arr[] = self::bindSaveData($v[$field], $tableName, $v['id']);
        }
        $res = self::saveAll($arr);
        return $res;
    }

}
