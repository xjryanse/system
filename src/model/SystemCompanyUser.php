<?php
namespace xjryanse\system\model;

/**
 * 公司员工表
 */
class SystemCompanyUser extends Base
{
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        [
            'field'     =>'user_id',
            // 去除prefix的表名
            'uni_name'  =>'user',
            'uni_field' =>'id',
            'del_check' => true,
            'del_msg'   => '该用户有职位信息，请先清理'
        ],
        [
            'field'     =>'dept_id',
            // 去除prefix的表名
            'uni_name'  =>'system_company_dept',
            'uni_field' =>'id',
        ],
        [
            'field'     =>'job_id',
            // 去除prefix的表名
            'uni_name'  =>'system_company_job',
            'uni_field' =>'id'
        ],
    ];
    /**
     * 20240102：司机列表
     * @return string
     */
    /*
    public static function driverSql(){
        $sql = '(select a.id,a.dept_id,a.user_id,a.sort,a.remark,b.role_key,b.job_name'
                . ',c.realname,c.address,c.phone,c.username,c.insure_desc,c.user_code'
                . ' from w_system_company_user as a'
                . ' inner join w_system_company_job as b on a.job_id = b.id'
                . ' inner join w_user as c on a.user_id = c.id)';

        return $sql;
    }
    */
    
}