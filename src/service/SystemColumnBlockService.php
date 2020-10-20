<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 系统块表
 */
class SystemColumnBlockService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemColumnBlock';

    public static function listsInfo($con = array()) {
        $info = self::lists($con);
        foreach($info as &$v){
            $con1               = [];
            $con1[]             = ['block_id','=',$v['id']];
            $tablesInfo         = SystemColumnBlockTableFieldsService::column("*","table_name");
            $v['tablesInfo']    = $tablesInfo;
        }
        return $info;
    }
}
