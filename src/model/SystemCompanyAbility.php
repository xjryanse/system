<?php
namespace xjryanse\system\model;

/**
 * 公司应用权限
 */
class SystemCompanyAbility extends Base
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
        [
            'field'     =>'bind_company_id',
            'uni_name'  =>'system_company',
            'uni_field' =>'id',
            'del_check' => true
        ],
//        [
//            'field'     =>'id',
//            'uni_name'  =>'finance_statement_order',
//            'uni_field' =>'belong_table_id',
//            'exist_field'=>'isStatementOrderExist',
//            'in_list'   => false,
//            'in_statics'=> false,
//            'in_exist'  => true,
//            'del_check' => false,
//        ],
    ];
    /**
     * 20230807：反置属性
     * @var type
     */
    public static $uniRevFields = [
        [
            'table'     =>'finance_statement_order',
            'field'     =>'belong_table_id',
            'uni_field' =>'id',
            'exist_field'   =>'isStatementOrderExist',
            'condition'     =>[
                // 关联表，即本表
                'belong_table'=>'{$uniTable}'
            ]
        ],
        [
            'table'     =>'wechat_we_pub_template_msg_log',
            'field'     =>'from_table_id',
            'uni_field' =>'id',
            'exist_field'   =>'isWechatWePubTemplateMsgLogExist',
            'condition'     =>[
                // 关联表，即本表
                'from_table'=>'{$uniTable}'
            ]
        ],
        
    ];
    
}