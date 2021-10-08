<?php
namespace xjryanse\system\logic;

use xjryanse\logic\Arrays;
use xjryanse\logic\WxBrowser;
use xjryanse\system\service\SystemCompanyService;
use xjryanse\wechat\service\WechatWeAppService;
use think\facade\Request;
use Exception;
/**
 * 公司逻辑
 */
class CompanyLogic
{

    /**
     * 路由>参数>请求头
     * @return type
     * @throws Exception
     */
    public static function hasCompany()
    {
        //先取路由参数
        $comKey     = Request::route('comKey');
        //再取请求参数
        if(!$comKey || mb_strlen($comKey) != 8){
            $comKey     = Request::param('comKey','') ? : session(SESSION_COMPANY_KEY);
        }
        // 取请求头参数
        if(!$comKey || mb_strlen($comKey) != 8){
            $comKey     = Request::header('comkey','') ? : session(SESSION_COMPANY_KEY);
        }
        //20210723，微信环境下，有传appid（小程序），拿一下公司key
        if(!$comKey && WxBrowser::isWxBrowser() && Request::header('appid','')){
        //if(!$comKey && Request::header('appid','')){
            // 兼容前端放在请求头
            $comKey = WechatWeAppService::appidGetComKey(Request::header('appid',''));
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
