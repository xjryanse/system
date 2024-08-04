<?php

namespace xjryanse\system\service\ability;

use xjryanse\logic\Arrays2d;

/**
 * 分页复用列表
 */
trait ListTraits{
        /**
     * 20230806：uniqid，组织树状
     */
    public static function listTree($param = []) {
        $con    = [];
        $ids    = self::column('id', $con);
        $allArr = self::extraDetails($ids);

        Arrays2d::sort($allArr, 'sort');

        return Arrays2d::makeTree($allArr,'','pid','subLists');
    }

}
