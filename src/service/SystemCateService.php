<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays2d;
/**
 * 分类表
 */
class SystemCateService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    // 静态模型：配置式数据表
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemCate';

    /**
     * 分组筛选
     * @param type $groupKey    key
     * @param type $con         条件
     * @param type $field       字段
     * @return type
     */
    public static function listsByGroup($groupKey, $con = [], $field = "*") {
        $con[] = ['group_key', '=', $groupKey];
        $res = self::mainModel()->where($con)->field($field)->select();
        return $res;
    }
    /**
     * 键值对的数组
     * @param type $groupKey    key
     * @param type $con
     */
    public static function columnByGroup( $groupKey, $con = [])
    {
        $con[] = ['group_key','=',$groupKey];
        //$res = self::mainModel()->where( $con )->cache(86400)->column('cate_name,class','cate_key');
        $array = self::staticConColumn('cate_name,class,cate_key', $con);
        return Arrays2d::fieldSetKey($array, 'cate_key');   //20220405前端bug无法解决，可能有冲突？？
        //20220322注释
        /*
        $res = Arrays2d::fieldSetKey($array, 'cate_key');
        return $res;
         * 
         */
    }
    /**
     * key取id
     */
    public static function keyGetId( $groupKey, $cateKey )
    {
        $con[] = ['group_key','=',$groupKey];
        $con[] = ['cate_key','=',$cateKey];
        return self::mainModel()->where( $con )->value('id');
    }
    
    public static function getByGroupKeyCateKey( $groupKey, $cateKey )
    {
        $con[] = ['group_key','=',$groupKey];
        $con[] = ['cate_key','=',$cateKey];
        return self::find( $con );
    }
    /*     * ***************** */

    /**
     *
     */
    public function fId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fAppId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fCompanyId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 分组key
     */
    public function fGroupKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 分类key
     */
    public function fCateKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 分类名
     */
    public function fCateName() {
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
