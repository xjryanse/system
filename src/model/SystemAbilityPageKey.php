<?php
namespace xjryanse\system\model;

/**
 * 系统能力对应的关联页面
 */
class SystemAbilityPageKey extends Base
{
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        [
            'field'     =>'ability_id',
            'uni_name'  =>'system_ability',
            'uni_field' =>'id',
            'del_check' => true
        ],
    ];

}