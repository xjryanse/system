<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
/**
 * 浏览日志
 */
class SystemScanLogService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemScanLog';


    public static function log( $scanItem, $scanItemId, $userId, $data = [])
    {
        $data['scan_item']      = $scanItem;
        $data['scan_item_id']   = $scanItemId;
        $data['user_id']        = $userId;

        return self::save( $data );
    }
}
