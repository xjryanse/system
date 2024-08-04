<?php

namespace xjryanse\system\service\importTempMatch;

/**
 * 
 */
trait DimTraits{
    /*
     * 提取用户的岗位列表
     */
    public static function dimImportTargetColumnsByKey($key){
        $con    = [];
        $con[]  = ['key', '=', $key];
        $lists  = self::staticConList($con);

        return array_column($lists, 'target_column', 'import_column');
    }

}
