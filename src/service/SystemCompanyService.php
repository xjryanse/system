<?php
namespace app\company\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 公司端口
 */
class SystemCompanyService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemCompany';

}
