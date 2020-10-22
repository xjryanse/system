<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 公司端口
 */
class SystemCompanyService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemCompany';

    /**
     * 根据key取值
     * @param type $key
     * @return type
     */
    public static function getByKey( $key )
    {
        $con[] = ['key','=',$key ];
        return self::find($con);
    }
}
