<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 存储时段数据，例如周；季等非标时段
 */
class SystemTimeService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemTime';

    /**
     * 
     * @param type $year
     * @return bool
     */
    public static function yearWeeklyInit($year){
        $start = strtotime($year . '-01-01'); // 获取当前年份第一天的时间戳
        $end = strtotime($year . '-12-31'); // 获取当前年份最后一天的时间戳

        $n = 1;
        for ($i = $start; $i <= $end; $i = strtotime('+1 day', $i)) { // 遍历一整年的每一天
            if (date('N', $i) == 1) { // 如果是星期一
                $tmp                = [];
                $tmp['cate']        = 'weekly';
                $tmp['start_time']  = date('Y-m-d 00:00:00', $i);
                $tmp['end_time']    = date('Y-m-d 23:59:59', strtotime('+6 day', $i));
                $tmp['time_name']   = $year.'年第'.$n.'周'; 
                self::saveRam($tmp);
                $n ++;
            }
        }

        return true;
    }
    
}
