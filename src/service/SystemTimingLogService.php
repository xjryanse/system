<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
/**
 * 系统定时器日志
 */
class SystemTimingLogService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemTimingLog';
}
