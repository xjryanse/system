<?php
namespace xjryanse\system\abstracts;

/**
 * 20220619处理方法抽象类
 */
abstract class MethodAbstract
{
    /**
     * 入口方法
     */
    public static function index($dataRaw){
        //①数据校验
        $data       = self::check($dataRaw);
        //②数据出库
        $dbOut      = self::dbOut($data);
        //③数据处理
        $dealData   = self::deal($data, $dbOut);
        //④数据入库
        $res        = self::dbIn($dealData);
        //结果返回
        return $res;
    }
    
    abstract protected static function check($data);
    abstract protected static function dbOut($data);
    /**
     * $data: 外输入数据
     * $dbOut:数据库提取的数据
     */
    abstract protected static function deal($data, $dbOut);
    abstract protected static function dbIn($data);
}
