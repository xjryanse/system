<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 分类表
 */
class SystemHostBindService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemHostBind';
    
    /**
     * 根据域名取绑定公司
     * @param type $host
     * @return type
     */
    public static function getBindCompanyIdByHost( $host )
    {
        $con[]  = [ 'host','=',$host ];
        $info   = self::find( $con ,86400 );
        return $info ? $info['bind_company_id'] : '';
    }
}
