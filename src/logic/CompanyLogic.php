<?php
namespace xjryanse\system\logic;

use xjryanse\logic\Arrays;
use xjryanse\logic\WxBrowser;
use xjryanse\system\service\SystemCompanyService;
use xjryanse\system\service\SystemHostBindService;
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
        //优先级1：先取路由参数
        $comKey     = Request::route('comKey');
        //优先级2：再取请求参数
        if(!$comKey || mb_strlen($comKey) != 8){
            $comKey     = Request::param('comKey','') ? : session(SESSION_COMPANY_KEY);
        }
        //优先级3：取请求头参数
        if(!$comKey || mb_strlen($comKey) != 8){
            $comKey     = Request::header('comkey','') ? : session(SESSION_COMPANY_KEY);
        }
        //优先级4：20210723，微信环境下，有传appid（小程序），拿一下公司key
        if(!$comKey && WxBrowser::isWxBrowser() && Request::header('appid','')){
            // 兼容前端放在请求头
            $comKey = WechatWeAppService::appidGetComKey(Request::header('appid',''));
        }
        //优先级5：查询当前域名是否有绑定端口，有的话提取
//        if(!$comKey){
//            $companyId = SystemHostBindService::getBindCompanyIdByHost(Request::host());
//            if($companyId){
//                $comKey = SystemCompanyService::getInstance( $companyId )->fKey();
//            }
//        }

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

    
    /**
     * 20230531:可操作校验
     */
    public static function checkCanOperate(){
        $id = session(SESSION_COMPANY_ID);
        if(!SystemCompanyService::getInstance($id)->canOperate()){
            throw new Exception('当前端口已过期，请续费');
        }
    }

}
