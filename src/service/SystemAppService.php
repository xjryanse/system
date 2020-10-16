<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use think\facade\Cache;
/**
 * 跨系统应用信息
 */
class SystemAppService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemApp';
    /*
     * 根据Appid获取id
     */
    public static function getIdByAppId( $appid)
    {
        $con[] = ['appid','=',$appid];
        $info = self::find( $con );
        return $info ? $info['id'] : '';
    }
    /***********************************************************************************/
    /**
     * 生成一个code
     */
    public function code($timestamp)
    {
        $code = $timestamp . randomKeys( 10 );
        //设缓存
        Cache::set( $this->codeKey(),$code);
        return $code;
    }
    /**
     * code缓存键名
     */
    public function codeKey()
    {
        return 'code' . $this->uuid . $this->fAppid();
    }    
    /**
     * 校验code
     */
    public function checkCode($code )
    {
        $cacheCode = Cache::get($this->codeKey());
        if($cacheCode == $code ) {
            Cache::set($this->codeKey(),'');
            return true;
        } else {
            return false;            
        }
    }
    /***********************************************************************************/    
    /**
     * 生成一个code
     */
    public function fAppid()
    {
        $info = $this->get();
        return $info ? $info['appid'] : '';
    }
    /**
     * 密钥
     * @return type
     */
    public function fSecret()
    {
        $info = $this->get();
        return $info ? $info['secret'] : '';
    }
    
    /**
     * 校验是否正确
     * @param type $encrypt     加密码
     * @param type $timestamp   时间戳
     * @return boolean
     */
    public function checkSignature($encrypt,$timestamp)
    {
        $myEncrypt         = appEncrypt($this->fAppid(), $this->fSecret(), $timestamp);

        return $encrypt == $myEncrypt;
    }
    /***************************************************************************/
    /**
     * 获取新token
     */
    public function token()
    {
        $token = randomKeys( 64 );
        
        $tokens = Cache::get( $this->tokenKey() );

        if(is_array($tokens)){
            foreach($tokens as $k=>$v){
                if( time() - $k >= 3600){
                    unset($tokens[$k]);
                }
            }
        }
        $time   = time();
        $tokens[ $time ] = $token;
        //设缓存
        Cache::set( $this->tokenKey(), $tokens );
        //设id
        Cache::set( $token, $this->uuid, 3600 );

        return $token;
    }
    /**
     * 校验token是否合法
     * @param type $token
     */
    public function checkToken( $token )
    {
        $cacheCode = Cache::get($this->tokenKey());
        return in_array( $token, $cacheCode );
    }
    /**
     * 校验token是否合法
     * @param type $token
     */
    public function cacheToken( )
    {
        return Cache::get($this->tokenKey());
    }
    /**
     * token键
     */
    public function tokenKey()
    {
        return 'token' . $this->uuid . $this->fAppid();
    }
    /**
     * token取appid
     */
    public static function tokenAppId($token)
    {
        if($token == 'xjryanse'){
            //调试专用token
            return 'debug';
        }
        return Cache::get( $token );
    }
    
}
