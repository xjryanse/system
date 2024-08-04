<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\wechat\service\WechatWePubFansUserService;

/**
 * 事项管理员
 * baoApply:包车申请
 * busFixApply:维修申请
 */
class SystemManageService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemManage';

    /**
     * 20230501：后台管理权限用户
     * @param type $key
     * @param type $con
     */
    public static function adminManageUserIds($key, $con = []) {
        $con[] = ['manage_type', '=', 'admin'];
        $con[] = ['manage_key', '=', $key];
        $userIds = self::staticConColumn('user_id', $con);
        return $userIds;
    }

    /**
     * 20230501：后台管理用户openid,接收消息
     */
    public static function adminManageOpenids($key, $con = []) {
        $con[] = ['accept_msg', '=', '1'];
        $userIds = self::adminManageUserIds($key, $con);

        $cond = [];
        $cond[] = ['user_id', 'in', $userIds];
        $openids = WechatWePubFansUserService::column('openid', $cond);
        return $openids;
    }

}
