<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use think\facade\Env;
use xjryanse\logic\File;
/**
 * 导出日志记录
 */
class SystemExportLogService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemExportLog';

    /**
     * 添加导出任务
     * @param type $info        文件名
     * @param type $titleArr    标题数组
     * @param type $sql         查询Sql
     * @param type $data        额外数据
     */
    public static function addTask($info, $titleArr, $sql, $data = []){
        $data['info']       = $info;
        $data['filed_json'] = json_encode($titleArr,JSON_UNESCAPED_UNICODE);
        $data['search_sql'] = $sql;
        return self::save($data);
    }
    
    /**
     * 自动删除超过30天的文件
     */
    public static function deleteExpire()
    {
        $con[] = ['finish_time','<=',date("Y-m-d",strtotime("-30 day"))];  //30天前的数据
        $lists = self::mainModel()->where( $con )->select();
        foreach( $lists as $v){
            self::getInstance($v['id'])->delete();
        }
    }
    
    /**
     * 删除同时删除文件
     */
    public function extraPreDelete(){
        $info = $this->get();
        $pathFull = Env::get('ROOT_PATH') .'public'.$info['file_path'];
        File::unlink($pathFull);
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
     * 导出信息说明
     */
    public function fInfo() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fModule() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 导出文件名称
     */
    public function fFileName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 文件路径
     */
    public function fFilePath() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 文件表头
     */
    public function fFiledJson() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 查询sql语句
     */
    public function fSearchSql() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 0待生成；1生成中；2已生成；3已下载
     */
    public function fExpStatus() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fFinishTime() {
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
