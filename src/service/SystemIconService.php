<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

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
        $con[] = [ 'group','=',$group];
        return self::lists( $con );
    }
}
