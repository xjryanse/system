<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays2d;
use xjryanse\logic\Datetime;

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
    /**
     * 按日期范围，统计浏览人数
     */
    public static function staticsByDayScope( $startTime, $endTime )
    {
        $startTime1  = date( 'Y-m-d 00:00:00',strtotime( $startTime ));
        $endTime1    = date( 'Y-m-d 23:59:59',strtotime( $endTime )  );

        $con[] = ['create_time','>=',  $startTime1 ];
        $con[] = ['create_time','<', $endTime1 ];

        $data = self::mainModel()->where($con)
                    ->field("date_format( create_time, '%Y-%m-%d' ) dat, count( * ) coun")
                    ->group("date_format( create_time, '%Y-%m-%d' )")
                    ->select();
        $dates = Datetime::getWithinDate( $startTime1 , $endTime1 );
        $res = Arrays2d::noValueSetDefault($data ? $data->toArray() : [] , 'dat', $dates , 'coun',0);
        return $res;
    }
}
