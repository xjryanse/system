<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use think\Db;

/**
 * 达成条件
 */
class SystemConditionService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\DebugTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemCondition';

    /*
     * itemid获取达成条件
     */

    public static function listsByItemId($itemId, $param = []) {
        $con[] = ['item_id', 'in', $itemId];
        return self::getCond($con, $param);
    }

    /**
     * itemKey获取达成条件
     * @param type $itemType
     * @param type $itemKey
     * @param type $param
     * @return type
     */
    public static function listsByItemKey($itemType, $itemKey, $param = []) {
        $con[] = ['item_type', 'in', $itemType];
        $con[] = ['item_key', 'in', $itemKey];
        $con[] = ['status', '=', 1];

        return self::getCond($con, $param);
    }
    
    protected static function getCond( $con, $param )
    {
        $lists = json_encode( self::lists($con, 'group_id') ,JSON_UNESCAPED_UNICODE);
        self::debug( '$param', $param );
        self::debug( '查询结果Sql-1', $lists );
        if($param){
            foreach ($param as $key => $value) {
                $lists = str_replace('{$' . $key . '}', $value, $lists);
            }
        }
        self::debug( '查询结果Sql-2', $lists );
        $listsRes = json_decode($lists, true);
        self::debug( '查询结果Sql-3', $listsRes );
        foreach( $listsRes as $key=>&$value){
            $value['judge_cond']    = json_decode($value['judge_cond'], JSON_UNESCAPED_UNICODE);
        }
        self::debug( '查询结果Sql-4', $listsRes );

        return $listsRes;
    }

    /**
     * 根据itemId,判断条件是否达成
     */
    public static function isReachByItemId($itemId, $param) {
        //条件
        $conditions = self::listsByItemId($itemId, $param);
        $results = self::conditionsGetResult($conditions);
        //相同group的数据，全部为true，则true;

        return $results;
    }

    /**
     * 根据itemKey，判断条件是否达成
     * @param type $itemType
     * @param type $itemKey
     * @param type $param
     */
    public static function isReachByItemKey($itemType, $itemKey, $param) {
        //条件
        $conditions = self::listsByItemKey($itemType, $itemKey, $param);
        self::debug(__METHOD__ . '0001', $conditions);
        $results = self::conditionsGetResult($conditions);
        self::debug(__METHOD__ . '结果', $results);
        //相同group的数据，全部为true，则true;
        return $results;
    }

    /**
     * 条件取结果
     */
    private static function conditionsGetResult($conditions) {
        //结果集
        $res = [];
        foreach ($conditions as &$v) {
            $tmpResult = Db::table($v['judge_table'])->where($v['judge_cond'])->field($v['judge_field'] . ' as result')->find();
            self::debug(__METHOD__ . '$tmpResultLastSql', Db::table($v['judge_table'])->getLastSql());
            $v['resVal'] = $tmpResult['result'];
            $v['result'] = eval("return " . $tmpResult['result'] . ' ' . $v['judge_sign'] . ' ' . $v['judge_value'] . ';');
            $res[$v['group_id']][] = $v['result'];
        }
        self::debug(__METHOD__ . '$res', $res );
        foreach ($res as $value) {
            //某一组全为true（没有false）,说明条件达成，
            if (!in_array(false, $value)) {
                return true;
            }
        }
        return false;
    }

    /*     * * */

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
     * 项目id，item_id和（item_type，item_key）用一组就可以
     */
    public function fItemId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 项目类型：同一类型同一key做筛选
     */
    public function fItemType() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 项目key
     */
    public function fItemKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 分组id，同一项目，同一组，全部达成，则达成
     */
    public function fGroupId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 判定表
     */
    public function fJudgeTable() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * count(*),sum(field)
     */
    public function fJudgeField() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * where条件
     */
    public function fJudgeCond() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 结果条件：>,=,<,>=,<= ……
     */
    public function fJudgeSign() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 结果值：1,2,3……
     */
    public function fJudgeValue() {
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
