<?php
namespace app\company\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 配置接口
 */
class SystemConfigsService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemConfigs';

}
