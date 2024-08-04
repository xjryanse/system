<?php

namespace xjryanse\system\service\authJobRole;

use xjryanse\logic\Arrays;
use xjryanse\user\service\UserAuthRoleService;
/**
 * 
 */
trait TriggerTraits{
/**
     * 钩子-保存前
     */
    public static function extraPreSave(&$data, $uuid) {

    }

    /**
     * 钩子-保存后
     */
    public static function extraAfterSave(&$data, $uuid) {

    }

    /**
     * 钩子-更新前
     */
    public static function extraPreUpdate(&$data, $uuid) {

    }

    /**
     * 钩子-更新后
     */
    public static function extraAfterUpdate(&$data, $uuid) {
        
    }

    /**
     * 钩子-删除前
     */
    public function extraPreDelete() {
        
    }

    /**
     * 钩子-删除后
     */
    public function extraAfterDelete() {
        
    }
            
    
    /**
     * 钩子-保存前
     */
    public static function ramPreSave(&$data, $uuid) {
        if(Arrays::value($data, 'role_key') && !Arrays::value($data, 'role_id')){
            $data['role_id'] = UserAuthRoleService::keyToId($data['role_key']);
        }
        // 20240102:写入
    }

    /**
     * 钩子-保存后
     */
    public static function ramAfterSave(&$data, $uuid) {

    }

    /**
     * 钩子-更新前
     */
    public static function ramPreUpdate(&$data, $uuid) {

    }
}
