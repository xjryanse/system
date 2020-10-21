<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\system\service\SystemColumnBtnService;
use xjryanse\system\service\SystemColumnBlockTableFieldsService;

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
            $tablesInfo         = SystemColumnBlockTableFieldsService::mainModel()->where($con1)->column("*","table_name");
            $v['tablesInfo']    = $tablesInfo;
            //获取操作按钮
            $v['btnInfo']       = SystemColumnBtnService::mainModel()->where($con1)->select();
            foreach($v['btnInfo'] as &$vv){
                $vv = SystemColumnBtnService::btnCov( $vv );
            }
        }
        return $info;
    }
}
