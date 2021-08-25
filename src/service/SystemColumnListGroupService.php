<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays2d;
use xjryanse\logic\Arrays;
use Exception;
/**
 * 字段分组
 */
class SystemColumnListGroupService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemColumnListGroup';

    public static function getColumnListInfo($columnId){
        $con[] = ['column_id','=',$columnId];
        $con[] = ['status','=',1];
        $columnListGroup = self::lists($con,'sort','id,width,align,column_lists,group_name');
        //拼上字段
        $columnListsObj = SystemColumnListService::lists($con);
        foreach($columnListsObj as &$v){
            $v['option'] = SystemColumnListService::getOption( $v['type'], $v['option']);
        }
        $columnLists = $columnListsObj ? $columnListsObj->toArray() : [];
        $columnListArr = Arrays2d::fieldSetKey($columnLists, 'id');
        //字段组循环
        foreach($columnListGroup as &$value){
            $arr    = json_decode($value['column_lists'],JSON_UNESCAPED_UNICODE);
            $tempArr = [];
            //字段行循环
            foreach($arr as $v){
                $temppp = [];
                //每个字段循环
                foreach($v as $columnListId){
                    $temppp[] = Arrays::value($columnListArr, $columnListId,[]);
                }
                $tempArr[] = $temppp;
            }
            //增加新的字段
            $value['columnListInfo'] = $tempArr;
            
            $value['test'] = $arr;
        }
        return $columnListGroup;
    }
    /**
     * 从column_list表保存数据
     */
    public static function addByColumnIdFromList($columnId){
        $con[] = ['column_id','=',$columnId];
        if(self::count($con)){
            throw new Exception('SystemColumnListGroup的columnId'.$columnId.'已有记录');
        }
        if(!SystemColumnListService::count($con)){
            throw new Exception('SystemColumnListService的columnId'.$columnId.'没有记录');
        }
        $data = SystemColumnListService::mainModel()->where($con)->field('column_id,label as group_name,concat(\'[["\',id,\'"]]\') as column_lists')->select();
        return self::saveAll($data->toArray());
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
    public function fColumnId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fGroupName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 字段id
     */
    public function fColumnLists() {
        return $this->getFFieldValue(__FUNCTION__);
    }
    /**
     * 方法Key
     */
    public function fWidth() {
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
