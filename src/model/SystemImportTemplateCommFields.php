<?php
namespace xjryanse\system\model;

/**
 * 导入模板
 */
class SystemImportTemplateCommFields extends Base
{
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        [
            'field'     =>'template_id',
            // 去除prefix的表名
            'uni_name'  =>'system_import_template',
            'uni_field' =>'id',
        ]
    ];
}