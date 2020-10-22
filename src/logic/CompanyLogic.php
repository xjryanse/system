<?php
namespace xjryanse\system\logic;

use xjryanse\logic\Arrays;
use xjryanse\system\service\SystemCompanyService;
/**
 * 公司逻辑
 */
class CompanyLogic
{
    /**
     * 根据key取公司
     */
    public static function getByKey( $param )
    {
        //从checkCompany搬过来-开始
        $comKey = Arrays::value( $param, 'comKey'); 
        if(!$comKey){
            throw new Exception('comKey必须');
        }
        
        return SystemCompanyService::getByKey( $key );
    }


}
