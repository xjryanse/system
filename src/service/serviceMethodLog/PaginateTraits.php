<?php

namespace xjryanse\system\service\serviceMethodLog;

use Exception;

/**
 * 分页复用列表
 */
trait PaginateTraits {

    /**
     * 20230806：公司选择分页
     */
    public static function paginateForUniqidGroup($con = []) {
        $res = self::where($con)->group('uniqid')->field('count(1) as num, uniqid')->paginate();
        return $res ? $res->toArray() : [];
    }
    
    
}
