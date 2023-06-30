<?php
namespace xjryanse\system\logic;

use think\facade\Cache;
use xjryanse\logic\Debug;
/**
 * 数据导出逻辑
 */
class RedisLogic
{
    /**
     * 暂存redis的数据批量写入数据库
     */
    public static function writeToDbAll(){
        $redisClasses = Cache::get('redisLogClasses') ? : [];
        Debug::debug('$redisClasses', $redisClasses);
        foreach($redisClasses as $class){
            $class::redisToDb();
        }
    }

}
