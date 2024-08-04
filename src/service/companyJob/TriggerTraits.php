<?php

namespace xjryanse\system\service\companyJob;

use xjryanse\logic\Arrays;
use xjryanse\phyexam\service\PhyexamItemJobService;
use xjryanse\system\service\SystemAuthJobRoleService;
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
        //有传权限数据，则保存权限
        $itemIds = Arrays::value($data, 'phyexamItemIds', []);
        if ($itemIds) {
            PhyexamItemJobService::savePhyexamItems($uuid, $itemIds);
        }
        //20220423保存角色
        if (isset($data['roleIds'])) {
            //保存角色信息
            $roleIds = Arrays::value($data, 'roleIds', []);
            //先删再加
            SystemAuthJobRoleService::jobRoleIdSave($uuid, $roleIds);
        }
    }

    public static function ramAfterSave(&$data, $uuid) {

    }
    
    public static function ramPreUpdate(&$data, $uuid) {
        //有传权限数据，则保存权限
        $itemIds = Arrays::value($data, 'phyexamItemIds', []);
        if ($itemIds) {
            PhyexamItemJobService::savePhyexamItems($uuid, $itemIds);
        }

        if (isset($data['roleIds'])) {
            //保存角色信息
            $roleIds = Arrays::value($data, 'roleIds', []);
            //先删再加
            SystemAuthJobRoleService::jobRoleIdSave($uuid, $roleIds);
        }
    }
    
    public static function ramAfterUpdate(&$data, $uuid) {

    }

    public function ramPreDelete() {

    }
}
