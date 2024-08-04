<?php
namespace xjryanse\system\model;

/**
 * 工作岗位资格证
 */
class SystemCompanyJobCertKey extends Base
{
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        [
            'field'     =>'job_id',
            // 去除prefix的表名
            'uni_name'  =>'system_company_job_cert_key',
            'uni_field' =>'id',
            'del_check' => true,
            'del_msg'   => '该岗位有关联资格证，请先解绑'
        ]
    ];
}