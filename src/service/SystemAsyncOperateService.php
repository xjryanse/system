<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
/**
 * 系统单线程异步实施类库
 */
class SystemAsyncOperateService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemAsyncOperate';
    
}
