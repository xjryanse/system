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
        $fields      = [];
        $fields[]    = 'count(1) as num';
        $fields[]    = 'uniqid';
        $fields[]    = 'max(create_time) as createTime';
        $fields[]    = 'max(ip) as ip';
        $fields[]    = 'max(url) as url';
        $fields[]    = 'max(source) as source';
        
        $res = self::where($con)->group('uniqid')
                ->field(implode(',',$fields))
                ->order('createTime desc')
                ->paginate();
        return $res ? $res->toArray() : [];
    }
    
    
}
