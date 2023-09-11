<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\wechat\service\WechatWeAppService;
use xjryanse\customer\service\CustomerUserService;
use xjryanse\wechat\WeApp;
use xjryanse\dev\logic\ProjectLogic;
use xjryanse\curl\Query;

use xjryanse\logic\DbOperate;
use xjryanse\system\logic\RedisLogic;
use xjryanse\logic\Cachex;
/**
 * 公司端口
 */
class SystemCompanyService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
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
    /*     * *本类定义变量**************** */
    protected $wechatWeApp = [];

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    return $lists;
                },true);
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
     *
     */
    public function fId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fAppId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公司名称
     */
    public function fName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    public function fLogo() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 地区码
     * @return type
     */
    public function fAreaCode() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公司编号
     */
    public function fCompanyNo() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公司简称
     */
    public function fShortName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公司类型
     */
    public function fCompanyCate() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 成立时间
     */
    public function fLaunchTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 经营范围
     */
    public function fManagementContent() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 省
     */
    public function fProvince() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 市
     */
    public function fCity() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 县
     */
    public function fDistrict() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公司地址
     */
    public function fAddress() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 营业执照编号
     */
    public function fLicence() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 法人姓名
     */
    public function fFrName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 法人手机
     */
    public function fFrMobile() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 接口访问key
     */
    public function fKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 小程序appid
     */
    public function fWeAppId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公众号acid
     */
    public function fWePubId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    public function fDevProjectId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 创建公司
     */
    public function fCreateCompanyId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 状态(0禁用,1启用)
     */
    public function fStatus() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 有使用(0否,1是)
     */
    public function fHasUsed() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 锁定（0：未锁，1：已锁）
     */
    public function fIsLock() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 锁定（0：未删，1：已删）
     */
    public function fIsDelete() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 备注
     */
    public function fRemark() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 创建者
     */
    public function fCreater() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 更新者
     */
    public function fUpdater() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 创建时间
     */
    public function fCreateTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 更新时间
     */
    public function fUpdateTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

}
