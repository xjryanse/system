<?php

namespace xjryanse\system\service\dataCompare;

use xjryanse\logic\BaseSystem;
/**
 * 
 */
trait DoTraits{

    /**
     * 20240713:从远端同步数据
     */
    public static function doDataSync(){
        $param              = [];
        $param['tableName'] = self::mainModel()->getTable();

        $list = BaseSystem::baseSysGet('/system/data_sync/newSave', $param);
        // 20240713:字符的转义：TODO:通用化
        foreach($list as &$v){
            $v['main_cond_filter']  = addslashes($v['main_cond_filter']);
            $v['sub_cond_filter']   = addslashes($v['sub_cond_filter']);
        }
        
        if($list){
            self::saveAllRam($list);
        }
        return true;
    }
    /**
     * 清空本表数据（以便后续源站拉取）
     */
    public static function doDataClear(){
        
    }

}
