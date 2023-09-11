<?php
namespace xjryanse\system\model;

/**
 * 系统试用记录
 */
class SystemCompanyAbilityTryLog extends Base
{
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        [
            'field'     =>'company_ability_id',
            'uni_name'  =>'system_company_ability',
            'uni_field' =>'id',
            'in_statics'=> true,
            'del_check' => true
        ],
    ];

    
}