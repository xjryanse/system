<?php

namespace xjryanse\system\service\serviceMethodLog;

use Exception;
use xjryanse\logic\Arrays2d;
use xjryanse\logic\Arrays;
/**
 * 分页复用列表
 */
trait ListTraits {
    /**
     * 20230806：uniqid，组织树状
     */
    public static function listUniqidTree($param) {
        $uniqid = Arrays::value($param,'uniqid');
        $con    = [];
        $con[]  = ['uniqid','=',$uniqid];

        $all    = self::where($con)->select();
        $allArr = $all ? $all->toArray() : [];

        return Arrays2d::makeTree($allArr,'','pid','subLists');
    }
}
