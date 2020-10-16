<?php
namespace app\scolumn\service;

use xjryanse\system\interfaces\MainModelInterface;
use think\facade\Request;
/**
 * 数据表按钮
 */
class SystemColumnBtnService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemColumnBtn';
    
    public static function btnCov( &$btnInfo )
    {
        $btnInfo['param']             = $btnInfo['param'] ? json_decode( $btnInfo['param'],true ) : [];
        $btnInfo['show_condition']    = $btnInfo['show_condition'] ? json_decode( $btnInfo['show_condition'],true ) : [];

        $tmp    = $btnInfo['url'];
        $tmp    .= strstr($tmp, '?') 
                ? '&'.'comKey='.Request::param('comKey','')
                : '/comKey/'. Request::param('comKey','');
        $btnInfo['url']  = $tmp;
        return $btnInfo;
    }
}
