<?php

namespace xjryanse\system\service\companyUser;


/**
 * 分页复用列表
 */
trait TriggerTraits{
    public static function extraPreSave(&$data, $uuid) {
        // self::stopUse(__METHOD__);
    }
    public static function extraPreUpdate(&$data, $uuid) {
        // self::stopUse(__METHOD__);
    }
    public function extraPreDelete() {
        // self::stopUse(__METHOD__);
    }
    /**
     * 20230814:引入原价和折扣价格的概念
     * @param type $data
     * @param type $uuid
     */
    public static function ramPreSave(&$data, $uuid) {
    }

    public static function ramAfterSave(&$data, $uuid) {
        self::getInstance($uuid)->certInit();
    }
    
    public static function ramPreUpdate(&$data, $uuid) {

    }
    
    public static function ramAfterUpdate(&$data, $uuid) {
        self::getInstance($uuid)->certInit();
    }

    public function ramPreDelete() {

    }
    
    
    
}
