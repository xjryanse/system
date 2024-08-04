<?php
namespace xjryanse\system\model;

/**
 * 工作岗位表
 */
class SystemCompanyJob extends Base
{
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        [
            'field'     =>'dept_id',
            // 去除prefix的表名
            'uni_name'  =>'system_company_dept',
            'uni_field' =>'id',
            'del_check' => true,
            'del_msg'   => '该部门有岗位，请先删除'
        ]
    ];
}