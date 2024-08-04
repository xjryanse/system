<?php
namespace xjryanse\system\model;

/**
 * 公司部门
 */
class SystemCompanyDept extends Base
{
    use \xjryanse\traits\ModelUniTrait;    
    public static $uniFields = [
        [
            'field'     =>'bind_customer_id',
            // 去除prefix的表名
            'uni_name'  =>'customer',
            'uni_field' =>'id',
        ],
    ];
}