<?php
namespace xjryanse\system\model;

/**
 * 字段明细
 */
class SystemColumnListForeign extends Base
{
    public function setIsCountAttr( $value )
    {
        //非布尔值时转布尔值
        return is_numeric($value) ? $value : booleanToNumber($value);
    }
}