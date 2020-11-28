<?php
namespace xjryanse\system\interfaces;

/**
 * 列表字段逻辑
 */
interface ColumnListInterface
{
    /**
     * 获取字段信息
     * @param type $optionStr   &符号连接的字符串
     */
    public static function getOption( $optionStr );

    /**
     * 获取数据
     * @param type $data
     * @param type $option
     */
    public static function getData( $data, $option );
}
