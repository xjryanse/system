<?php

namespace xjryanse\system\service\companyAbility;

use xjryanse\logic\Arrays;
use Exception;
/**
 * 分页复用列表
 */
trait TriggerTraits{
    public static function extraPreSave(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }
    public static function extraPreUpdate(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }
    public function extraPreDelete() {
        self::stopUse(__METHOD__);
    }
    /**
     * 20230814:引入原价和折扣价格的概念
     * @param type $data
     * @param type $uuid
     */
    public static function ramPreSave(&$data, $uuid) {
        $data['discount_prize'] = Arrays::value($data,'discount_prize',0); 
        $data['original_prize'] = Arrays::value($data,'original_prize',0); 
        if($data['discount_prize'] > $data['original_prize']){
            throw new Exception('折让价不可大于原价');
        }
        // 原价-折让金额
        $data['prize'] = $data['original_prize'] - $data['discount_prize'];
        return $data;
    }

    public static function ramAfterSave(&$data, $uuid) {
        if(Arrays::value($data, 'prize')){
            $pData['discount_prize'] = $data['discount_prize'];
            $pData['original_prize'] = $data['original_prize'];

            self::getInstance($uuid)->changePrizeRam($data['prize'], $pData);
        }
        self::getInstance($uuid)->updateDataRam();
    }
    
    public static function ramPreUpdate(&$data, $uuid) {
        if(isset($data['original_prize'])){
            $data['discount_prize'] = Arrays::value($data,'discount_prize',0); 
            $data['original_prize'] = Arrays::value($data,'original_prize',0); 
            if($data['discount_prize'] > $data['original_prize']){
                throw new Exception('折让价不可大于原价');
            }
            // 原价-折让金额
            $data['prize'] = $data['original_prize'] - $data['discount_prize'];
            
            $pData['discount_prize'] = $data['discount_prize'];
            $pData['original_prize'] = $data['original_prize'];
            self::getInstance($uuid)->changePrizeRam($data['prize'], $pData);
        }

        return $data;
    }
    
    public static function ramAfterUpdate(&$data, $uuid) {
        self::getInstance($uuid)->updateDataRam();
    }
    
    public function ramPreDelete() {
        $info = $this->get();
        if($info['prize'] && $info['has_buy']){
            throw new Exception('客户已购不可删');
        }
        if($info['has_try']){
            throw new Exception('客户已试用，请先删除试用记录');
        }
    }
}
