<?php

namespace xjryanse\system\service;

use xjryanse\logic\Url;
use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Debug;

/**
 * 分享日志
 */
class SystemShareLogService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemShareLog';

    /**
     * 生成新的分享
     */
    public static function newShare( $url, $recUserId , $shareBy="",$data=[])
    {
        $data['id']             = self::mainModel()->newId();
        $data['url']            = Url::addParam($url, ['shareId'=>$data['id']]);
        $data['rec_user_id']    = $recUserId;
        $data['share_by']       = $shareBy;
        Debug::debug('微信分享的数据保存', $data);
        $res = self::save($data);
        //顺带清理一下没用的数据
        self::noShareClear();
        return $res;
    }
    
    public function setShare($data=[])
    {
        $data['is_share'] = 1;
        return $this->update($data);
    }

    /**
     * 清理未分享的数据
     */
    protected static function noShareClear()
    {
        $con[] = ['is_share','=',0];
        $con[] = ['create_time','<=',date('Y-m-d H:i:s',strtotime('-2 hour'))];
        return self::mainModel()->where( $con )->delete();
    }
    /**
     *
     */
    public function fId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 应用id
     */
    public function fAppId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公司id
     */
    public function fCompanyId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 访问ip
     */
    public function fIp() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fUrl() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 请求头部
     */
    public function fHeader() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 请求参数
     */
    public function fParam() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 访问模块
     */
    public function fModule() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 访问控制器
     */
    public function fController() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 访问方法
     */
    public function fAction() {
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
