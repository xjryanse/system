<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 分类表
 */
class SystemCateService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemCate';

    /**
     * 分组筛选
     * @param type $groupKey    key
     * @param type $con         条件
     * @param type $field       字段
     * @return type
     */
    public function listsByGroup( $groupKey, $con = [],$field="*") {
        $con[] = ['group_key','=', $groupKey ];
        $res = self::mainModel()->where( $con )->field( $field )->select();
        return $res;
    }
}
