<?php
namespace xjryanse\system\logic;

use xjryanse\logic\Arrays;
use xjryanse\system\service\SystemCompanyService;
use think\facade\Request;
use Exception;
/**
 * 公司逻辑
 */
class CompanyLogic
{

    /**
     * 
     * @return type
     * @throws Exception
     */
    public static function hasCompany()
    {
        //先取路由参数
        $comKey     = Request::route('comKey');
        if(!$comKey){
            //再取请求参数
            $comKey     = Request::param('comKey','') ? : session(SESSION_COMPANY_KEY);
        }
        if( !$comKey ){
            throw new Exception('请求入口错误');
        }
        $info = SystemCompanyService::getByKey( $comKey );
        if( !$info){
            throw new Exception('未找到company信息'.$comKey);
        }
        session(SESSION_COMPANY_KEY,$comKey);
        session(SESSION_COMPANY_ID,$info['id']);        
        return $info;
    }


}
