<?php
namespace app\scolumn\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 操作表
 */
class SystemColumnOperateService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemColumnOperate';

}
