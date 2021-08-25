<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;
use think\Db;
/**
 * 字段明细
 */
class SystemColumnListService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemColumnList';

    public static function dynDataList($columnId,$data){
        $con[] = ['column_id','=',$columnId];
        $con[] = ['type','=','dynenum'];    //类型为动态枚举
        $optionBase = self::mainModel()->where($con)->column('option','name');
        $optionArr = [];
        foreach($optionBase as $key=>$option){
            $option = equalsToKeyValue($option);
            $tableName  = Arrays::value($option, 'table_name');
            $tableKey   = Arrays::value($option, 'key');
            $value      = Arrays::value($option, 'value');
            $cond = [];
            $cond[] = [$tableKey,'in',$data[$key]];
            $arr = Db::table($tableName)->where($cond)->column($value,$tableKey);
            $optionArr[$key] = $arr;
        }
        return $optionArr;
    }
    /**
     * 额外详情信息
     */
    protected static function extraDetail(&$item, $uuid) {
        if(!$item){ return false;}
        self::commExtraDetail($item,$id );
        $columnId = isset($item['column_id']) ? $item['column_id'] : '';
        $item['cate_field_name'] = SystemColumnService::getInstance($columnId)->fCateFieldName();
        return $item;
    }

    /**
     * 选项转换
     * @param type $type        类型
     * @param type $optionStr   选项字符串
     * @return type
     */
    public static function getOption($type, $optionStr,$data=[]) {
        $class = self::getClassStr($type);
        return class_exists($class) ? $class::getOption($optionStr,$data) : '';
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
    public static function columnTypeFields( $columnId,$typeName,$con = [])
    {
        $con[] = ['status','=',1];
        $con[] = ['column_id','=',$columnId];
        $con[] = ['type','=',$typeName];
        return self::column('distinct name', $con);
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
