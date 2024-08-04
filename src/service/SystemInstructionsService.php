<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Cachex;

/**
 * 系统使用说明
 */
class SystemInstructionsService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;


    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemInstructions';

    
    /**
     * 根据来源表id，提取说明id
     * 当没有记录时，自动生成一条
     */
    public static function getIdByFromTableIdWithGenerate($fromTable, $fromTableId){
        $con    = [];
        $con[]  = ['from_table','=',$fromTable];
        $con[]  = ['from_table_id','=',$fromTableId];
        $id = self::where($con)->value('id');
        if(!$id){
            // 没有就生成
            $data['from_table']     = $fromTable;
            $data['from_table_id']  = $fromTableId;
            $id = self::saveGetIdRam($data);
        }
        return $id;
    }
    
}
