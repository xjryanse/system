<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 导入模板
 */
class SystemImportTemplateDealFieldsService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemImportTemplateDealFields';

    /**
     * 20240101：获取转换数组
     */
    public static function getFieldsCov($dealId, $con = []){
        $con[] = ['deal_id','=',$dealId];
        $lists = self::staticConList($con);
        return array_column($lists, 'target_field','import_field');
    }
    /**
     * 20240101：获取转换数组
     */
    public static function getUniqueFieldsCov($dealId, $con = []){
        $con[] = ['deal_id','=',$dealId];
        $con[] = ['is_unique','=',1];
        $lists = self::staticConList($con);
        return array_column($lists, 'target_field','import_field');
    }
}
