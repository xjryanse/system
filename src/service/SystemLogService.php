<?php
namespace app\log\service;

use xjryanse\system\interfaces\MainModelInterface;
use think\facade\Request;
/**
 * 访问日志
 */
class SystemLogService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemLog';

    public static function log()
    {
        try{
            $data['ip']         = Request::ip();
            $data['url']        = Request::url();
            $data['header']     = json_encode(Request::header(),JSON_UNESCAPED_UNICODE);
            $data['param']      = json_encode(Request::param(),JSON_UNESCAPED_UNICODE);
            $data['module']     = Request::module();
            $data['controller'] = Request::controller();
            $data['action']     = Request::action();
            self::save($data);
        } catch (\Exception $e) {
            //不报异常，以免影响访问
        }
    }
}
