<?php
namespace xjryanse\system\interfaces;

/**
 * 额外数据接口
 */
interface ExtraDataInterface
{
    /**
     * 额外详情：用于获取数据
     * 拆解数据，数据存在val中
     */
    public static function extraDetail( &$item ,$uuid );
    /**
     * 额外输入信息：用于保存数据
     * @param type $data
     * @param type $uuid
     */
    public static function extraPreSave(&$data,$uuid );
}
