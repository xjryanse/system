<?php
namespace xjryanse\system\model;

/**
 * 字段明细
 */
class SystemColumnList extends Base
{
    
    /**
     * 关联外键
     * @param type $value
     * @return type
     */
    public function setFlexItemIdAttr($value)
    {
        return $value ? : null;
    }
}