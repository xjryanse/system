<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Debug;
use xjryanse\logic\DbOperate;
use xjryanse\logic\Arrays;
use xjryanse\order\service\OrderService;
use Exception;
use think\Db;

/**
 * 达成条件
 */
class SystemConditionService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;
    use \xjryanse\traits\StaticModelTrait;
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
        Debug::debug('listsByItemKey查询条件', $con);
        return self::getCond($con, $param);
    }

    protected static function getCond($con, $param) {
        //20220814优化
        $listsRaw = self::staticConList($con);
        $lists = json_encode($listsRaw, JSON_UNESCAPED_UNICODE);
        //20210625不缓存
        // $lists = json_encode( self::lists($con, 'group_id','*',86400) ,JSON_UNESCAPED_UNICODE);
        self::debug('$param测', $param);
        self::debug('查询结果Sql-1', $lists);
        if ($param) {
            self::debug('if($param)中打印', $param);
            foreach ($param as $key => $value) {
                self::debug('$key', $key);
                self::debug('$value', $value);
                if(is_array($value) || is_object($value)){
                    continue;
                }
                $lists = str_replace('{$' . $key . '}', $value, $lists);
            }
            self::debug('$lists', $lists);
            self::debug('-------------------', '');
        }
        self::debug('查询结果Sql-2', $lists);
        $listsRes = json_decode($lists, true);
        self::debug('查询结果Sql-3', $listsRes);
        foreach ($listsRes as $key => &$value) {
            $value['judge_cond'] = json_decode($value['judge_cond'], JSON_UNESCAPED_UNICODE);
        }
        self::debug('查询结果Sql-4', $listsRes);

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
        self::debug(__METHOD__ . $itemKey . '0001', $conditions);
        $results = self::conditionsGetResult($conditions, $param);
        self::debug(__METHOD__ . $itemKey . '结果', $results);
        //相同group的数据，全部为true，则true;
        return $results;
    }

    /**
     * 根据itemKey，从表中查询一条记录。
     * 判断条件是否达成
     * @param type $itemType
     * @param type $itemKey
     * @param type $param
     */
    public static function findDataByItemKey($itemType, $itemKey, $param) {
        $con[] = ['item_type', '=', $itemType];
        $con[] = ['item_key', '=', $itemKey];
        $info = self::getCond($con, $param);
        if (!$info) {
            return false;
        }
        //从数据来源表中取相关的数据
        $service = DbOperate::getService($info[0]['judge_table']);
        $data = $service::find($info[0]['judge_cond']);
        return ['from_table' => $info[0]['judge_table'], 'from_table_data' => $data->toArray()];
    }

    /**
     * 条件取结果
     */
    private static function conditionsGetResult($conditions, $param = []) {
        if (!$conditions) {
            return false;
        }
        //结果集
        self::debug(__METHOD__ . '$conditions', $conditions);
        $res = [];
        foreach ($conditions as &$v) {
            //校验条件字段是否存在：人性化异常，避免开发人员找半天
            $service = DbOperate::getService($v['judge_table']);
            foreach ($v['judge_cond'] as $v2) {
                if (!$service::mainModel()->hasField($v2[0])) {
                    throw new Exception($v['judge_table'] . '表没有字段' . $v2[0]);
                }
            }
            //结果
            $res[$v['group_id']][] = self::conditionResult($v, $param);
        }
        self::debug(__METHOD__ . '$res', $res);
        foreach ($res as $value) {
            //某一组全为true（没有false）,说明条件达成，
            if (!in_array(false, $value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 单条件取结果
     * @param type $condition   条件
     * @param type $param       入参
     */
    private static function conditionResult($condition, $param) {
        self::debug(__METHOD__ . '判断查询结果的$condition', $condition);
        //结果
        $itemType = Arrays::value($condition, 'item_type');
        $judgeTable = Arrays::value($condition, 'judge_table');
        $judgeCond = Arrays::value($condition, 'judge_cond', []);
        $judgeField = Arrays::value($condition, 'judge_field');
        $judgeSign = Arrays::value($condition, 'judge_sign');
        $judgeValue = Arrays::value($condition, 'judge_value');
        //符号替换
        $signReplace['='] = '==';   // 等号'
        $signReplace['<>'] = '!=';  //不等号'
        //TODO订单表，使用特殊处理；（因param参数即从订单表来，为了节约数据库io开销，尝试简化）
        if ($itemType == 'order' && $judgeTable == OrderService::mainModel()->getTable()) {
            foreach ($judgeCond as $cond) {
                //符号替换
                $sign = Arrays::value($signReplace, $cond[1], $cond[1]);
                $evalStr = 'return \'' . $param[$cond[0]] . '\' ' . $sign . ' \'' . $cond[2] . '\';';
                self::debug(__METHOD__ . '判断查询结果的eval语句', $evalStr);
                if (!isset($param[$cond[0]]) || !eval($evalStr)) {
                    return false;
                }
            }
            return true;
        } else {
            try{
                $tmpResult = Db::table($judgeTable)->master()->where($judgeCond)->field($judgeField . ' as result')->find();
                self::debug(__METHOD__ . '判断查询结果的sql语句', Db::table($judgeTable)->getLastSql());
            } catch(\Exception $e){
                Debug::dump(Db::table($judgeTable)->getLastSql());
                throw $e;
            }
            $code = 'return \'' . $tmpResult['result'] . '\' ' . $judgeSign . ' ' . $judgeValue . ';';
            return eval($code);
        }
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
