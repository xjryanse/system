<?php
namespace xjryanse\system\logic;

use xjryanse\system\service\SystemFlexItemsService;
/**
 * 布局逻辑
 */
class FlexLogic
{
    use \xjryanse\traits\TreeTrait;    
    /**
     * 获取flex布局
     */
    public static function getFlex( $flexId )
    {
        $con[] = ['flex_id','=',$flexId];
        $data = SystemFlexItemsService::lists( $con );
        return self::makeTree($data);
    }
}
