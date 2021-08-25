<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Cachex;
/**
 * 访问日志
 */
class SystemIconService implements MainModelInterface {
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemIcon';

    /**
     * 根据group取数据列表
     * @param type $group
     * @return type
     */
    public static function listsByGroup( $group )
    {
        $cacheKey = session(SESSION_COMPANY_ID).'_SystemIconService_'.$group;
        $result = Cachex::funcGet( $cacheKey, function() use ($group){
            $con[] = [ 'group','=',$group];
            $con[] = [ 'status','=',1];
            $lists = self::lists( $con,'sort','id,icon_name,icon_img,url');
            if($lists){
                $lists = $lists->toArray();
            }
            foreach($lists as &$v){
                if(isset($v['icon_img']['base64_brief'])){
                    unset($v['icon_img']['base64_brief']);
                }
            }
            return $lists;
        });
        return $result;
    }
}
