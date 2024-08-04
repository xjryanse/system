<?php

namespace xjryanse\system\service\companyJob;

use xjryanse\user\service\UserAuthAccessService;
use xjryanse\logic\Arrays2d;
/**
 * 
 */
trait ListTraits{
    /**
     * 获取岗位下挂菜单
     */
    public static function listMenu($param){
        $con = [];
        $lists = UserAuthAccessService::where($con)->order('sort')->select();
        $allArr = $lists ? $lists->toArray() : [];
        
        return $allArr;
    }
}
