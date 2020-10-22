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
        //从checkCompany搬过来-开始
        $comKey     = Request::param('comKey','') ? : session('scopeCompanyKey');
        if( !$comKey ){
            throw new Exception('请求入口错误');
        }
        $info = SystemCompanyService::getByKey( $comKey );
        if( !$info){
            throw new Exception('未找到company信息');
        }
        session('scopeCompanyKey',$comKey);
        session('scopeCompanyId',$info['id']);        
        return $info;
    }


}
