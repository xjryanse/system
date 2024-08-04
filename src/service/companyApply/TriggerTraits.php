<?php

namespace xjryanse\system\service\companyApply;

use Exception;
use xjryanse\logic\Arrays;
use xjryanse\logic\DataCheck;
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
        if(!Arrays::value($data, 'user_id') ){
            $data['user_id'] = session(SESSION_USER_ID);
        }
        // 20231206
        $keys = ['cate','level','user_id','comp_name','realname','phone'];
        DataCheck::must($data, $keys);
    }

    public static function ramAfterSave(&$data, $uuid) {

    }
    
    public static function ramPreUpdate(&$data, $uuid) {

    }
    
    public static function ramAfterUpdate(&$data, $uuid) {

    }
    
    public function ramPreDelete() {
        $info = $this->get();
        if($info['new_company_id']){
            throw new Exception('端口'.$info['new_company_id'].'已开通不可删');
        }
    }
}
