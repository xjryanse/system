<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\wechat\service\WechatWeAppService;
use xjryanse\customer\service\CustomerUserService;
use xjryanse\customer\service\CustomerService;
use xjryanse\user\service\UserService;
use xjryanse\wechat\WeApp;
use xjryanse\dev\logic\ProjectLogic;
use xjryanse\curl\Query;
use xjryanse\logic\DbOperate;
use xjryanse\logic\Arrays;
use xjryanse\logic\Cachex;
use xjryanse\logic\Strings;
use xjryanse\system\logic\RedisLogic;
use Exception;

/**
 * 公司端口
 */
class SystemCompanyService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\ObjectAttrTrait;

// 静态模型：配置式数据表
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemCompany';
    //一经写入就不会改变的值
    protected static $fixedFields = ['id', 'name', 'logo', 'key', 'we_app_id'
        , 'we_pub_id', 'creater', 'create_time'];
    ///从ObjectAttrTrait中来
    // 定义对象的属性
    protected $objAttrs = [];
    // 定义对象是否查询过的属性
    protected $hasObjAttrQuery = [];
    // 定义对象属性的配置数组
    protected static $objAttrConf = [
        'systemCompanyUser' => [
            'class' => '\\xjryanse\\system\\service\\SystemCompanyUserService',
            'keyField' => 'company_id',
            'master' => true
        ],
        'financeAccount' => [
            'class' => '\\xjryanse\\finance\\service\\FinanceAccountService',
            'keyField' => 'company_id',
            'master' => true
        ]
    ];
    
    use \xjryanse\system\service\company\FieldTraits;
    
    /*     * *本类定义变量**************** */
    protected $wechatWeApp = [];

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    return $lists;
                },true);
    }
    /**
     * 提取当前公司的信息
     * 20231119
     */
    public static function current(){
        $companyId = session(SESSION_COMPANY_ID);
        $info = self::getInstance($companyId)->get();
        return $info;
    }

    /**
     * 根据key取值
     * @param type $key
     * @return type
     */
    public function getKey() {
        $info = $this->get();
        return $info ? $info['key'] : '';
    }

    /**
     * 获取公司绑定的公众号APP
     * @return type
     */
    public function getWechatWeApp() {
        if (!$this->wechatWeApp) {
            $appId = $this->fWeAppId();
            $info = WechatWeAppService::getInstance($appId)->get();
            $this->wechatWeApp = new WeApp($info['appid'], $info['secret'], './runtime/');
        }
        return $this->wechatWeApp;
    }

    /**
     * 根据key取值
     * @param type $key
     * @return type
     */
    public static function getByKey($key) {
        $con[] = ['key', '=', $key];
        return self::staticConFind($con);
        //return self::find($con,86400);
    }
    /**
     * 公司全称取端口
     * 20231117
     * @param type $name
     * @return type
     */
    public static function getByComp($name) {
        $con[] = ['name', '=', $name];
        return self::staticConFind($con);
        //return self::find($con,86400);
    }
    
    /**
     * key转id
     * @param type $comKey
     * @return type
     */
    public static function keyToId($comKey) {
        $con[] = ['key', '=', $comKey];
        $company = self::find($con);
        return $company ? $company['id'] : '';
    }

    /**
     * 20230524:关联的项目信息
     */
    public function projectInfo() {
        $devProjectId = $this->fDevProjectId();
        if (!$devProjectId) {
            return [];
        }
        return ProjectLogic::getInstance($devProjectId)->info();
    }

    /**
     * 20230531:端口是否可操作
     * 1，购买端口，过期了仍然可以操作
     * 2，租用端口，过期了则不能操作
     * @return type
     */
    public function canOperate() {
        $devProjectId = $this->fDevProjectId();
        return ProjectLogic::getInstance($devProjectId)->canOperate();
    }

    /**
     * 用户id，反查管理的公司id端口
     */
    public static function userCompanyIds($userId){
        $customerIds    = CustomerUserService::userManageCustomerIds($userId);
        $conComp[]  = ['bind_customer_id','in',$customerIds];
        return self::ids($conComp);
    }

    /**
     * 20230819:跨端清理缓存
     */
    public function crossCacheClear(){
        $info = $this->get();
        if($info['base_url']){
            // 清理本站
            self::cacheClear();
            // 跨端清理
            $clearUrl = $info['base_url'].'index/index/cacheClear';
            // 调用清缓存链接，例如：https://axsl.xiesemi.cn/index/index/cacheClear
            Query::geturl($clearUrl);
        }
    }
    
    /**
     * 
     */
    public static function cacheClear(){
        // 20221026
        RedisLogic::writeToDbAll();
        // 存放在redis的聊天记录需要搬到数据库，否则会造成数据丢失
        // ChatLogic::writeToDbAll();
        //清除缓存
        $excepKeys = ['devRequestIp'];
        Cachex::clearExcept($excepKeys);
        //数据库字段重新缓存一下,避免初次查询卡死
        $tableName = self::getTable();
        DbOperate::columns($tableName);
    }
    /*     * ***** */
    /**
     * 端口初始化
     * 20231117
     */
    public static function init($compName, $data = []){
        if(self::getByComp($compName)){
            throw new Exception($compName.'已有端口');
        }

        $data['name']   = $compName;
        // 随机8位当key
        $data['key']    = Strings::rand(8);
        // 20231117:id,数字
        $maxId          = self::where()->order('id desc')->value('id');
        $data['id']     = $maxId + 1;

        $res            = self::saveRam($data);
        //【2】账号初始化 TODO
        $phone = Arrays::value($data, 'fr_mobile');
        if($phone){
            UserService::compUserInit($data['id'], $phone);
        }
        // 初始化一些固定客户
        CustomerService::compCustomerInit($data['id']);
        
        return $res;
    }

}
