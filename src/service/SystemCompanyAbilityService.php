<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\system\service\SystemCompanyService;
use xjryanse\logic\Arrays;
use xjryanse\logic\Arrays2d;
use xjryanse\logic\Functions;
use xjryanse\finance\service\FinanceStatementService;
use xjryanse\finance\service\FinanceStatementOrderService;
use Exception;

/**
 * 跨系统应用信息
 */
class SystemCompanyAbilityService extends Base implements MainModelInterface {

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
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemCompanyAbility';
    
    // 20230710：开启方法调用统计
    protected static $callStatics = true;
    
    use \xjryanse\system\service\companyAbility\FieldTraits;
    use \xjryanse\system\service\companyAbility\PaginateTraits;
    use \xjryanse\system\service\companyAbility\TriggerTraits;
    use \xjryanse\system\service\companyAbility\CalTraits;

    
    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
            foreach ($lists as &$v) {
                // 是否可操作：最终能力
                $v['canOperate']    = self::calCanOperate($v) ? 1 : 0 ;
                // 20230814:折扣率
                $v['discRate']    = floatval($v['original_prize']) ? round($v['prize'] / $v['original_prize'] * 100 ,2) . '%' : '';
            }
            return $lists;
        },true);
    }

    /*
     * 20230815:计算是否需要付款了
     * 是否需付款状态标记
     */
    protected function dealNeedPaySet(){
        $hasTry             = $this->calHasTry();
        $res = $this->objAttrsList('financeStatementOrder');
        foreach($res as $v){
            // 20231030:增加,只提取能力项目
            if($v['statement_type'] != 'companyAbility'){
                continue;
            }
            if($hasTry){
                FinanceStatementOrderService::getInstance($v['id'])->setNeedPayRam();
            } else {
                FinanceStatementOrderService::getInstance($v['id'])->setNoNeedPayRam();
            }
        }
    }

    /**
     * 20230806：更新试用数据
     */
    public function updateDataRam(){
        $data['has_buy']            = $this->calHasPay();
        $data['has_try']            = $this->calHasTry();
        $data['try_finish_time']    = $this->calTryFinishTime() ? : null;
        // 20230819:清缓存处理：注意顺序
        $this->calUpdateDiffs($data);
        
        $res = $this->doUpdateRamClearCache($data);
        // 更新账单需付状态
        $this->dealNeedPaySet();
        // 20230819:跨端清缓存
        $this->crossCacheClear();

        return $res;
    }
    
    /**
     * 20230819调用跨端清缓存
     */
    public function crossCacheClear(){
        // 20230819:清缓存
        $fields = ['has_try','try_finish_time','has_buy'];
        // 字段有更新才清理
        if($this->updateDiffsHasField($fields)){
            // 有这些更新，则需要清理一下缓存数据，TODO,最好带上延时？？
            $info = $this->get();
            // 20230819:执行清理缓存的动作
            SystemCompanyService::getInstance($info['bind_company_id'])->crossCacheClear();
        }
    }
    
    /**
     * 校验公司是否有指定key的权限
     * @param type $key
     */
    public static function hasAbilityByKey($key, $companyId = '') {
        $cId = $companyId ?: session(SESSION_COMPANY_ID);
        if (!$cId) {
            return false;
        }
        $id = SystemAbilityService::keyToId($key);
        $con[] = ['ability_id', '=', $id];
        $con[] = ['bind_company_id', '=', $cId];
        $data = self::staticConFind($con);
        return $data ? true : false;
    }

    /**
     * 20220614 校验能力
     */
    public static function checkAbility($key, $companyId = '') {
        $cId = $companyId ?: session(SESSION_COMPANY_ID);
        if (!self::hasAbilityByKey($key, $cId)) {
            throw new Exception('该端口' . $cId . '没有开通相关权限' . $key);
        }
    }
    
    /**
     * 20230809:提取公司可操作能力（已开通 + 试用）
     */
    public static function canOperateAbilityIds($companyId){
        // companyId提取abilityid,
        $con[]  = ['bind_company_id','=',$companyId];
        $ids    = self::ids($con);
        $lists  = self::extraDetails($ids);
        // 提取有权限
        $conArr[]       = ['canOperate','=',1];
        $listsHasAuth   = Arrays2d::listFilter($lists, $conArr);
        $abilityIds     = array_column($listsHasAuth,'ability_id');
        return $abilityIds;
    }

    /**
     * 20230806:订单账单添加
     * 仅测试
     */
    public function addStatementOrder() {
        $info = $this->get();
        if (!$info) {
            throw new Exception('端口能力记录不存在' . $this->uuid);
        }
        $lists = $this->objAttrsList('financeStatementOrder');
        if($lists){
            throw new Exception('端口能力已有关联账单' . $this->uuid);
        }
        //20230806：端口能力开通
        $prizeKey       = 'companyAbility';
        $belongTable    = self::getTable();
        $companyInfo    = SystemCompanyService::getInstance($info['bind_company_id'])->get();
        if(!$companyInfo['bind_customer_id']){
            throw new Exception('端口' . $info['bind_company_id'].'未绑定客户');
        }

        $data['customer_id']    = Arrays::value($companyInfo, 'bind_customer_id');
        $data['user_id']        = session(SESSION_USER_ID);
        //原价，折扣价
        $data['original_prize'] = Arrays::value($info, 'original_prize');
        $data['discount_prize'] = Arrays::value($info, 'discount_prize');
        
        $res = FinanceStatementOrderService::belongTablePrizeKeySaveRam($prizeKey, $info['prize'], $belongTable, $this->uuid, $data);
        return $res;
    }
    /**
     * 20230807:改价格
     */
    public function changePrizeRam($newPrize, $data = []){
        $info = $this->get();
        if (!$info) {
            throw new Exception('端口能力记录不存在' . $this->uuid);
        }
        $abilityId = Arrays::value($info,'ability_id');
        //20230806：端口能力开通
        $prizeKey       = 'companyAbility';
        $belongTable    = self::getTable();
        $companyInfo    = SystemCompanyService::getInstance($info['bind_company_id'])->get();
        if(!$companyInfo['bind_customer_id']){
            throw new Exception('端口' . $info['bind_company_id'].'未绑定客户');
        }

        $data['customer_id']    = Arrays::value($companyInfo, 'bind_customer_id');
        $data['user_id']        = session(SESSION_USER_ID);
        
        $abilityInfo            = SystemAbilityService::getInstance($abilityId)->get();
        $data['statement_name'] = Arrays::value($abilityInfo, 'name') .' 功能费';

        return FinanceStatementOrderService::changePrizeRam($belongTable, $this->uuid, $newPrize, $prizeKey, $data);
    }
    /*     * ******************************************************************************** */

    /**
     * 生成一个code
     */
    public function code($timestamp) {
        $code = $timestamp . randomKeys(10);
        //设缓存
        Cache::set($this->codeKey(), $code);
        return $code;
    }

    /**
     * code缓存键名
     */
    public function codeKey() {
        return 'code' . $this->uuid . $this->fAppid();
    }

    /**
     * 校验code
     */
    public function checkCode($code) {
        $cacheCode = Cache::get($this->codeKey());
        if ($cacheCode == $code) {
            Cache::set($this->codeKey(), '');
            return true;
        } else {
            return false;
        }
    }

    /*     * ******************************************************************************** */

    /**
     * 校验是否正确
     * @param type $encrypt     加密码
     * @param type $timestamp   时间戳
     * @return boolean
     */
    public function checkSignature($encrypt, $timestamp) {
        $myEncrypt = appEncrypt($this->fAppid(), $this->fSecret(), $timestamp);

        return $encrypt == $myEncrypt;
    }

    /*     * ************************************************************************ */

    /**
     * 获取新token
     */
    public function token() {
        $token = randomKeys(64);

        $tokens = Cache::get($this->tokenKey());

        if (is_array($tokens)) {
            foreach ($tokens as $k => $v) {
                if (time() - $k >= 3600) {
                    unset($tokens[$k]);
                }
            }
        }
        $time = time();
        $tokens[$time] = $token;
        //设缓存
        Cache::set($this->tokenKey(), $tokens);
        //设id
        Cache::set($token, $this->uuid, 3600);

        return $token;
    }

    /**
     * 校验token是否合法
     * @param type $token
     */
    public function checkToken($token) {
        $cacheCode = Cache::get($this->tokenKey());
        return in_array($token, $cacheCode);
    }

    /**
     * 校验token是否合法
     * @param type $token
     */
    public function cacheToken() {
        return Cache::get($this->tokenKey());
    }

    /**
     * token键
     */
    public function tokenKey() {
        return 'token' . $this->uuid . $this->fAppid();
    }

    /**
     * token取appid
     */
    public static function tokenAppId($token) {
        if ($token == 'xjryanse') {
            //调试专用token
            return 'debug';
        }
        return Cache::get($token);
    }
    
    /**
     * 20230727：发起能力试用
     */
    public static function doAbilityTry($param = []){
        // 20230727:绑定的客户端口
        $companyId      = Arrays::value($param, 'bind_company_id');
        // 20230727
        $abilityId      = Arrays::value($param, 'ability_id');
        // 试用天数（默认30）
        $days           = Arrays::value($param, 'days', 30);
        $tryStartTime   = Arrays::value($param, 'start_time', date('Y-m-d H:i:s'));
        $tryFinishTime  = date('Y-m-d H:i:s', strtotime($tryStartTime) + $days * 86400);

        $companyAbilityId = self::companyAbilityGetIdRam($companyId, $abilityId);

        $data['company_ability_id'] = $companyAbilityId;
        $data['try_start_time']     = $tryStartTime;
        $data['try_finish_time']    = $tryFinishTime;
        return SystemCompanyAbilityTryLogService::saveRam($data);
    }
    /**
     * 20230806:单条
     */
    public static function doAbilityTryById($id, $param = []){
        $info = self::getInstance($id)->get();
        if(!$info){
            throw new Exception('公司能力信息不存在'.$id);
        }
        if($info['has_try']){
            throw new Exception('能力已试用'.$id);
        }
        if($info['has_buy']){
            throw new Exception('能力已开通'.$id);
        }

        $data['company_ability_id'] = $id;

        $days           = Arrays::value($param, 'days', 30);
        $tryStartTime   = Arrays::value($param, 'start_time', date('Y-m-d H:i:s'));
        $tryFinishTime  = date('Y-m-d H:i:s', strtotime($tryStartTime) + $days * 86400);

        $data['try_start_time']     = $tryStartTime;
        $data['try_finish_time']    = $tryFinishTime;
        return SystemCompanyAbilityTryLogService::saveRam($data);
    }
    /**
     * 批量试用
     * 20230806
     * @param type $ids
     */
    public static function doAbilityTryBatch($ids){
        return Functions::batchId($ids, function($id){
            return self::doAbilityTryById($id);
        });
    }

    /**
     * 20230727:公司和能力，提取一个id
     */
    public static function companyAbilityGetIdRam($companyId, $abilityId){
        $con[]  = ['bind_company_id','=',$companyId];
        $con[]  = ['ability_id','=',$abilityId];
        $id     = self::where($con)->value('id');
        if(!$id){
            $data['bind_company_id'] = $companyId;
            $data['ability_id'] = $abilityId;
            $id = self::saveGetIdRam($data);
        }
        return $id;
    }
    
    /*     * *************************************** */

    /**
     * 只提取指定公司，需要支付费用的页面
     */
    public static function allPageKeysByCompanyId($companyId){
        $con    = [];
        $con[]  = ['bind_company_id','=',$companyId];
        $abilityIds = self::where($con)->column('distinct ability_id');
        
        $cone   = [];
        $cone[] = ['ability_id','in',$abilityIds];
        $pageKeys = SystemAbilityPageKeyService::where($cone)->column('distinct page_key');
        
        return $pageKeys;
    }

    /*******处理财务回调******************/
    /**
     * 
     * @param type $info    FinanceStatementOrder的一条记录
     */
    public function dealFinanceCallBack($info){
        $this->updateDataRam();
    }
    /*
     * 获取批量账单id，用于合并支付
     */
    public static function statementGenerate($ids){
        $con[]              = ['belong_table_id','in',$ids];
        $statementOrderIds  = FinanceStatementOrderService::mainModel()->where($con)->column('id');
        $statementId        = FinanceStatementOrderService::getStatementIdWithGenerate($statementOrderIds, true);
        $financeStatement   = FinanceStatementService::getInstance( $statementId )->info();
        return $financeStatement;
    }
    
    
    
    
    
}
