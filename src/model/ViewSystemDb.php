<?php
namespace xjryanse\system\model;

/**
 * 
 */
class ViewSystemDb extends Base
{
    use \xjryanse\traits\ModelUniTrait;
    
    public static $picFields = ['buyer_sign','seller_sign'];
    // 20230516:数据表关联字段
    public static $uniFields = [
        [
            'field'     =>'table_name',
            'uni_name'  =>'system_column',
            'uni_field' =>'table_name',
            'in_list'   => false,
            'in_statics'=> false,
            'in_exist'  => true,
            'del_check' => false,
            'exist_field' =>'isColumnExist'
        ],
    ];

}