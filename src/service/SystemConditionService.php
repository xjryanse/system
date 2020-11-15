<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use think\Db;

/**
 * 达成条件
 */
class SystemConditionService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemCondition';

    /*
     * itemid获取达成条件
     */
    public static function listsByItemId( $itemId )
    {
        $con[] = ['item_id','in',$itemId ];
        return self::lists($con,'group_id');
    }
    /**
     * itemKey获取达成条件
     * @param type $itemId
     * @return type
     */
    public static function listsByItemKey( $itemType, $itemKey )
    {
        $con[] = ['item_type','in',$itemType ];
        $con[] = ['item_key','in',$itemKey ];
        return self::lists($con,'group_id');
    }
    /**
     * 根据itemId,判断条件是否达成
     */
    public static function isReachByItemId( $itemId, $param)
    {
        //条件
        $conditions = self::listsByItemId( $itemId );
        
    }
    /**
     * 根据itemKey，判断条件是否达成
     * @param type $itemType
     * @param type $itemKey
     * @param type $param
     */
    public static function isReachByItemKey( $itemType, $itemKey, $param )
    {
        //条件
        $conditions = self::listsByItemKey( $itemType, $itemKey );
        
    }
    
    
}
