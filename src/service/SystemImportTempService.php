<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Debug;
/**
 * 导入临时文件
 */
class SystemImportTempService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemImportTemp';
    
    /**
     * 获取插队后的处理条件
     * @param type $con     原始条件
     * @param type $max     最大插队批次数量
     * @param type $limit   批次数
     */
    public static function withCutCon( $con = [],$max = 50,$limit = 3)
    {
        //根据ident_key，获取批次数量
        $res = self::mainModel()->where($con)
                ->field('ident_key,count(1) as total')
                ->group('ident_key')
                ->having("total <= ".$max)
                ->limit($limit)
                ->order('total')
                ->select();
        Debug::debug('根据ident_key获取批次量结果Sql', self::mainModel()->getLastSql());
        Debug::debug('根据ident_key获取批次量结果', $res);
        $identKeys  = array_column( $res ? $res->toArray() : [], 'ident_key');
        if($identKeys){
            $identKeys = array_column( $res->toArray(), 'ident_key');
            $con[] = ['ident_key','in', $identKeys ];
        }
        return $con;
    }
    /**
     * 重新设为待处理
     */
    public static function resetTodo()
    {
        $con[] = ['update_time','<',date('Y-m-d H:i:s',strtotime('-2 minute'))];
        $con[] = ['operate_status','=',1];
        $ids = self::ids( $con );
        if($ids){
            $con[] = ['id','in',$ids];
            self::mainModel()->where($con)->update(['operate_status'=>0]);
        }
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
     * 导入数据表名
     */
    public function fTableName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fFileId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * todo,doing,finish
     */
    public function fOpStatus() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 返回消息
     */
    public function fRespMessage() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 预导数据
     */
    public function fPreData() {
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
