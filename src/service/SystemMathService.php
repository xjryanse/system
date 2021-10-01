<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Debug;
use xjryanse\logic\Cachex;
use Exception;
/**
 * 数学计算
 */
class SystemMathService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemMath';
    //避免死循环
    protected static $timesCount = 0;    

    public static function getByKey($key)
    {
        return Cachex::funcGet(__CLASS__.'_'.__METHOD__.$key, function() use ($key){
            $con[] = ['math_key','=',$key];
            return self::find($con);
        });
    }
    /**
     * 根据key，获取计算公式
     * @param type $key
     * @return type
     */
    public static function getStrByKey($key, $data = []){
        self::$timesCount = self::$timesCount +1;
        $limitTimes = 20;
        if(self::$timesCount > $limitTimes){
            throw new Exception('SystemMathService::getStrByKey死循环'.$limitTimes);
        }
        $info = self::getByKey($key);
        if(!$info){
            throw new Exception('计算公式key:'.$key.'不存在');
        }
        foreach ($data as $key => $value) {
            $info['first_value'] = str_replace('{$' . $key . '}', $value, $info['first_value']);
            $info['last_value'] = str_replace('{$' . $key . '}', $value, $info['last_value']);
        }
        if(!is_numeric($info['first_value'])){
            // 递归，注意避免死循环
            $info['first_value'] = self::getStrByKey($info['first_value'], $data);
        }
        if(!is_numeric($info['last_value'])){
            // 递归，注意避免死循环
            $info['last_value'] = self::getStrByKey($info['last_value'], $data);
        }
        
        $calStr = '(' . $info['first_value'] . ') '.$info['sign']. ' ('.$info['last_value'].')';
        return $calStr;
    }
    /**
     * 根据key，计算结果
     * @param type $key
     * @param type $data
     * @return type
     */
    public static function calByKey($key, $data = []){
        $calStr = self::getStrByKey($key, $data);
        Debug::debug('计算公式', $calStr);
        return eval( 'return '. $calStr .';' );
    }
    /**
     *
     */
    public function fId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fMathKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fMathDesc() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 
     */
    public function fFirstValue() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 
     */
    public function fSign() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 
     */
    public function fLastValue() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 排序
     */
    public function fSort() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 状态(0禁用,1启用)
     */
    public function fStatus() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 有使用(0否,1是)
     */
    public function fHasUsed() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 锁定（0：未锁，1：已锁）
     */
    public function fIsLock() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 锁定（0：未删，1：已删）
     */
    public function fIsDelete() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 备注
     */
    public function fRemark() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 创建者，user表
     */
    public function fCreater() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 更新者，user表
     */
    public function fUpdater() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 创建时间
     */
    public function fCreateTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 更新时间
     */
    public function fUpdateTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

}
