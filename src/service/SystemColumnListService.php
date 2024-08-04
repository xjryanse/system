<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;
use xjryanse\logic\Arrays2d;
use xjryanse\logic\DataList;
use xjryanse\logic\DbOperate;
use xjryanse\system\service\columnlist\Dynenum;
use think\Db;

/**
 * 字段明细
 */
class SystemColumnListService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;


// 静态模型：配置式数据表
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemColumnList';
    // 动态枚举选框数组
    protected static $sDynDataListArr = [];
    
    /**
     * 字段id，和原始数据数组，取动态枚举列表（list查询的dynDataList值）
     * @param type $columnId
     * @param type $dataArr
     */
    public static function getDynDataListByColumnIdAndData($columnId, $dataArr) {
        //        $dynFields          = self::columnTypeFields($columnId, 'dynenum');
        //        $dynDatas           = [];
        //        foreach($dynFields as $key){
        //            $dynDatas[$key] = array_unique(array_column($dataArr,$key));
        //        }
        // $res = self::sDynDataList($columnId, $dynDatas);

        $dynArrs = self::dynArrs($columnId);
        $res = Dynenum::dynDataList($dataArr, $dynArrs);

        return $res;
    }

    /**
     * 20230413：获取动态枚举配置
     * @param type $columnId
     * 'user_id'    =>'table_name=w_user&key=id&value=username'
     * 'goods_id'   =>'table_name=w_goods&key=id&value=goods_name'
     */
    public static function dynArrs($columnId) {
        $con[] = ['column_id', '=', $columnId];
        $con[] = ['type', '=', 'dynenum'];
        $lists = self::staticConList($con);
        return array_column($lists, 'option', 'name');
        // return self::where($con)->column('option','name');
    }

    /**
     * 获取动态数据，一般用于分页查询后处理
     * 似乎可以逐步淘汰了
     * 
      $dynArrs = self::dynArrs($columnId);
      $res = Dynenum::dynDataList($dataArr, $dynArrs);
     * 
     * @param type $columnId
     * @param type $data
     * @return array
     */
    public static function sDynDataList($columnId, $data = []) {
        //20220303更优化
        $con[] = ['column_id', '=', $columnId];
        $con[] = ['type', '=', 'dynenum'];    //类型为动态枚举
        $rr = SystemColumnListService::staticConList($con);
        $optionBase = Arrays2d::toKeyValue($rr, 'name', 'option');

        //Debug::debug('$optionBase',$optionBase);
        $optionArr = [];
        foreach ($optionBase as $key => $option) {
            $option = equalsToKeyValue($option);
            $tableName = Arrays::value($option, 'table_name');
            $tableKey = Arrays::value($option, 'key');
            $value = Arrays::value($option, 'value');
            //20220131增加判断
            if ($data[$key]) {
                if (is_array($data[$key]) && count($data[$key]) == 1 && !$data[$key][0]) {
                    //20220317增加判空
                    $arr = [];
                } else {
                    // 20230730:改造后
                    $arr = self::sDynDataQuery($tableName, $tableKey, $data[$key], $value);
                }
            } else {
                $arr = [];
            }
            $optionArr[$key] = $arr;
        }
        return $optionArr;
    }
    /**
     * 20230730:查询数据
     * @param type $tableName       w_user
     * @param type $tableKey        id
     * @param type $dataIds         [1,2,3]
     * @param type $valueField      namePhone
     * @return type
     */
    public static function sDynDataQuery($tableName, $tableKey, $dataIds, $valueField){
        // 拼接识别key：表名+id+值字段名
        $key = $tableName.'.'.$tableKey.'.'.$valueField;
        if(!isset(self::$sDynDataListArr[$key]) || !self::$sDynDataListArr[$key]){
            self::$sDynDataListArr[$key] = [];
        }
        // 20230730改造优化性能
        return DataList::dataObjAdd(self::$sDynDataListArr[$key], $dataIds, function($qIds) use ($tableName, $tableKey, $valueField){
            $cond = [];
            $cond[] = [$tableKey, 'in', $qIds];
            // 20230730:如果是静态表，不用查了
            $service = DbOperate::getService($tableName);
            if (method_exists($service, 'staticConList')) {
                // 20230730:如果是静态表，不用查了
                $lists = $service::staticConList($cond);
                $arr = array_column($lists, $valueField, $tableKey);
            } else {
                // 20230730原来的逻辑
                $inst = Db::table($tableName)->where($cond);
                $arr = $inst->cache(1)->column($valueField, $tableKey);
            }
            return $arr;
        });
    }

    /**
     * 额外详情信息
     */
    protected static function extraDetail(&$item, $uuid) {
        if (!$item) {
            return false;
        }
        self::commExtraDetail($item, $uuid);
        $columnId = isset($item['column_id']) ? $item['column_id'] : '';
        $item['cate_field_name'] = SystemColumnService::getInstance($columnId)->fCateFieldName();
        return $item;
    }

    /**
     * 20220804:通过表名获取搜索字段
     */
    public static function getSearchFields($tableName, $queryType = 'where') {
        $con[] = ['table_name', '=', $tableName];
        $info = SystemColumnService::staticConFind($con);
        if (!$info) {
            return [];
        }
        $cone[] = ['column_id', 'in', $info['id']];
        $lists = self::staticConList($cone);
        // 组装
        $searchFields = [];
        foreach ($lists as $v) {
            if ($v['search_type'] >= 0 && $v['query_type'] == $queryType) {
                $searchFields[$v['search_type']][] = $v['name'];
            }
        }
        return $searchFields;
    }

    /**
     * 选项转换
     * @param type $type        类型
     * @param type $optionStr   选项字符串
     * @return type
     */
    public static function getOption($type, $optionStr, $data = []) {
        $class = self::getClassStr($type);
        return class_exists($class) ? $class::getOption($optionStr, $data) : '';
    }

    /**
     * 获取数据
     * @param type $type        类型
     * @param type $data        数据
     * @param type $columnInfo  字段信息
     * @return type
     */
    public static function getData($type, $data, $columnInfo) {
        $class = self::getClassStr($type);
        if (class_exists($class)) {
            return $class::getData($data, $columnInfo);
        } else {
            return isset($data[$columnInfo['name']]) ? $data[$columnInfo['name']] : '';
        }
    }

    /**
     * 中间表保存数据
     */
    public static function saveData($type, $data, $columnInfo) {
        $class = self::getClassStr($type);
        return class_exists($class) ? $class::saveData($data, $columnInfo) : false;
    }

    /**
     * 获取class
     * @param type $type
     */
    private static function getClassStr($type) {
        return '\\xjryanse\\system\\service\\columnlist\\' . ucfirst($type);
    }

    /**
     * 
     * @param type $columnId    指定columnId
     * @param type $typeName    指定类型
     * @param type $con
     */
    public static function columnTypeFields($columnId, $typeName, $con = []) {
        $con[] = ['status', '=', 1];
        $con[] = ['column_id', '=', $columnId];
        $con[] = ['type', '=', $typeName];
        $array = self::staticConColumn('name', $con);
        return array_unique($array);
    }

    /**
     * 获取求和字段
     */
    public static function sumFields($columnId) {
        $con[] = ['status', '=', 1];
        $con[] = ['column_id', '=', $columnId];
        $con[] = ['is_sum', '=', 1];
        $array = self::staticConColumn('name', $con);
        return array_unique($array);
    }

    /**
     * 20230225：唯一字段，用于复制时加copy
     * @param type $columnId
     * @return type
     */
    public static function uniqueFields($columnId) {
        $con[] = ['status', '=', 1];
        $con[] = ['column_id', '=', $columnId];
        $con[] = ['is_unique', '=', 1];
        $array = self::staticConColumn('name', $con);
        return array_unique($array);
    }

    /*     * *********** */

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
     * 字段id
     */
    public function fColumnId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 方法Key
     */
    public function fMethodKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 字段名
     */
    public function fLabel() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 字段键名
     */
    public function fName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * {
      "hidden":"隐藏域",
      "text":"单行文字",
      "enum":"枚举",
      "image":"上传图片",
      "date":"日期",
      "datetime":"日期+时间",
      "textarea":"文本框",
      "editor":"编辑器",
      "number":"数值",
      "dynenum":"动态枚举",
      "10":"搜索选择框(文本)",
      "11":"搜索选择框(枚举)",
      "link":"跳转链接",
      "13":"链接图片",
      "14":"一级复选(中间表)",
      "15check":"二级复选(中间表)",
      "16switch":"开关",
      "17":"时间框",
      "18":"关联表",
      "19":"密码",
      "20":"联表数据",
      "21":"月日时分",
      "22":"百分比",
      "23":"上传附件",
      "24":"随机串(8位)",
      "25":"随机串(16位)",
      "26":"随机串(32位)",
      "27":"不可编辑框",
      "28":"后台换行设置(行颜色条件)",
      "29":"逗号分隔换行显示",
      "30":"链接二维码",
      "31-listedit":"列表编辑",
      "99":"混合字段"}
     */
    public function fType() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 选项json
     */
    public function fOption() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 搜索类型？'否、equal:精确查找;like:模糊查找;in:in查找;numberscope:数据范围查找;timescope:时间查找
     */
    public function fSearchType() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 列表字段？0否、1是
     */
    public function fIsList() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 列表编辑？0否、1是
     */
    public function fIsListEdit() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 添加字段？0否、1是
     */
    public function fIsAdd() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 编辑字段？0否、1是
     */
    public function fIsEdit() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 必填(新增和编辑时)？0否、1是
     */
    public function fIsMust() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 是否导出？0否，1是
     */
    public function fIsExport() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 是否导入必填，0否，1是，导入时，需要表单预填的基础信息
     */
    public function fIsImportMust() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 是否跨公司？0否，1是。用于跨公司查询数据
     */
    public function fIsSpanCompany() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 列表位置：left:靠左；right:靠右；center:居中
     */
    public function fListStyle() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 最小宽度px;
     */
    public function fMinWidth() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 列表中的最大宽度
     */
    public function fWidth() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 2-12
     */
    public function fFormCol() {
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
