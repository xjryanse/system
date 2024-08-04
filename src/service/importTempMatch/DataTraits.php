<?php

namespace xjryanse\system\service\importTempMatch;

use xjryanse\logic\Arrays;
/**
 * 前端 ajaxData 入口对接方法
 * 
 */
trait DataTraits{
    /*
     * 提取导入的字段
     */
    public static function dataImportTargetColumnsByKey($param){
        $importKey = Arrays::value($param, 'importKey');
        return self::dimImportTargetColumnsByKey($importKey);
    }

}
