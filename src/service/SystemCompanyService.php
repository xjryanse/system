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
    public function getKey( )
    {
        $info = $this->get();
        return $info ? $info['key'] : '';
    }    
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
    /**
     * key转id
     * @param type $comKey
     * @return type
     */
    public static function keyToId( $comKey )
    {
        $con[]      = [ 'key' , '=' , $comKey ];
        $company    = self::find($con);
        return $company ? $company['id'] : '' ;
    }
    /**
     * 默认访问小程序acid
     */
    public function fWeAppId()
    {
        $info = $this->get();
        return $info ? $info['we_app_id']: '';
    }    
    /**
     * 默认访问公众号acid
     */
    public function fWePubId()
    {
        $info = $this->get();
        return $info ? $info['we_pub_id']: '';
    }

}
