<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 导入模板
 */
class SystemImportTemplateCommFieldsService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\StaticModelTrait;
    use \xjryanse\traits\ObjectAttrTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemImportTemplateCommFields';

    /**
     * 20240101：获取转换数组
     */
    public static function getFieldsCov($templateId){
        $con   = [];
        $con[] = ['template_id','=',$templateId];
        $lists = self::staticConList($con);
        return array_column($lists, 'target_field','comm_field' );
    }
    
}
