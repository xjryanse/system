<?php
namespace xjryanse\system\model;

/**
 * 用户角色
 */
class SystemAuthJobRole extends Base
{
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        [
            'field'     =>'role_id',
            // 去除prefix的表名
            'uni_name'  =>'user_auth_role',
            'uni_field' =>'id',
            'del_check' => true,
            'del_msg'   => '该角色有岗位使用，请先解绑'
        ],
        [
            'field'     =>'job_id',
            // 去除prefix的表名
            'uni_name'  =>'system_company_job',
            'uni_field' =>'id',
            'in_list'   => false,
            'in_statics'=> true,
            'in_exist'  => true,
            'del_check' => true,
            'del_msg'   => '该岗位有绑定角色，请先解绑'
        ],
    ];


}