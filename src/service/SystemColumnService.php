<?php
namespace app\scolumn\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 数据表
 */
class SystemColumnService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemColumn';

}
