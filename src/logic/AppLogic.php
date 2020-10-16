<?php
namespace xjryanse\system\logic;

use xjryanse\logic\Arrays;
use xjryanse\system\service\SystemAppService;
use Exception;
/**
 * APP逻辑
 */
class AppLogic
{
    /**
     * 获取连接code
     * @return string
     */
    public static function code( $param )
    {
        $appid       = Arrays::value( $param, 'appid'); 
        $encrypt     = Arrays::value( $param, 'encrypt'); 
        $timestamp   = Arrays::value( $param, 'timestamp'); 
        if(!$appid || !$encrypt || !$timestamp){
            throw new Exception('参数不全');
        }
        $id     = SystemAppService::getIdByAppId($appid);
        if( !SystemAppService::getInstance( $id )->checkSignature($encrypt,$timestamp)){
            throw new Exception('参数校验失败');
        }
        $code   = SystemAppService::getInstance( $id )->code($timestamp);
        return $code;
    }
    /**
     * 获取token 
     * @return type
     */
    public static function token( $param )
    {
        $code   = Arrays::value( $param, 'code'); 
        //app在表中的id值
        $id     = Arrays::value( $param, 'id'); 
        if(!$code){
            throw new Exception('参数错误');
        }
        if( !SystemAppService::getInstance( $id )->checkCode($code)){
            throw new Exception('code校验失败');
        }
        $token = SystemAppService::getInstance( $id )->token($code);
        return $token;
    }
    /**
     * token拿公司信息
     */
    public static function tokenAppId( $param )
    {
        $token  = Arrays::value( $param, 'token'); 
        $id     = SystemAppService::tokenAppId($token);
        if( !$id || !SystemAppService::getInstance($id)->checkToken($token)){
            $data['current'] = $token;
            $data['arrays']  = SystemAppService::getInstance($id)->cacheToken();
            
            throw new Exception('token校验失败','1001');
        }
        //返回token的公司信息
        return $id;
    }
}
