<?php
namespace xjryanse\system\interfaces;

/**
 * 20220619：方法接口
 */
interface MethodInterface
{
    /**
     * 入口方法
     */
    public static function index($data);
    
    /*
     * 1必要数据校验
     */
    protected static function check($data);
    /**
     * 2数据出库
     * @param type $data
     */
    protected static function dbOut($data);
    /**
     * 数据处理
     * @param type $data
     */
    protected static function deal($data);
    /**
     * 数据入库
     * @param type $data
     */
    protected static function dbIn($data);
}
