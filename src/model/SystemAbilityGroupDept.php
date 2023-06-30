<?php
namespace xjryanse\system\model;

/**
 * 系统能力清单
 */
class SystemAbilityGroupDept extends Base
{
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        [
            'field'     =>'customer_id',
            // 去除prefix的表名
            'uni_name'  =>'customer',
            'uni_field' =>'id',
            'del_check' => true,
        ],
        [
            'field'     =>'ability_group_id',
            // 去除prefix的表名
            'uni_name'  =>'system_ability_group',
            'uni_field' =>'id',
            'del_check' => true,
        ],
        [
            'field'     =>'dept_id',
            // 去除prefix的表名
            'uni_name'  =>'system_company_dept',
            'uni_field' =>'id',
            'del_check' => true,
        ]
    ];

}