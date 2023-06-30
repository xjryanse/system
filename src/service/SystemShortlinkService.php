<?php

namespace xjryanse\system\service;

use xjryanse\logic\Url;
use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;
use xjryanse\logic\Strings;
use xjryanse\logic\Arrays2d;
use xjryanse\logic\Cachex;
use Exception;
use think\facade\Request;

/**
 * 系统短链接
 */
class SystemShortlinkService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemShortlink';

    /**
     * 20220903:key转长链接
     * @param type $key
     * @return type
     */
    public static function keyToLink($key) {
        $funcKey = __METHOD__;
        $list = Cachex::funcGet($funcKey, function() {
                    return self::selectX();
                });

        $con[] = ['link_key', '=', $key];
        $info = Arrays2d::listFind($list, $con);
        $linkRaw = $info ? $info['long_link'] : '';
        // 2022-12-01:增加字符串替换
        $link = Strings::dataReplace($linkRaw, $info);
        return $link;
    }

    /**
     * 
     * @param type $linkName        链接名称
     * @param type $longLink        长连接
     * @param type $belongTable     归属表
     * @param type $belongTableId   归属表编号
     */
    public static function generate($linkName, $longLink, $belongTable = '', $belongTableId = '') {
        if ($belongTableId) {
            $con[] = ['belong_table_id', '=', $belongTableId];
            $info = self::mainModel()->where($con)->find();
            if ($info) {
                throw new Exception('已有短链接');
            }
        }

        $data['belong_table'] = $belongTable;
        $data['belong_table_id'] = $belongTableId;
        $data['link_name'] = $linkName;
        $data['link_key'] = Strings::rand(6);
        $data['long_link'] = $longLink;
        return self::save($data);
    }

    /**
     * 2022-12-15
     * @param type $data
     * @param type $uuid
     * @return type
     */
    public static function extraPreSave(&$data, $uuid) {
        if (!Arrays::value($data, 'link_key')) {
            $data['link_key'] = Strings::rand(6);
        }
        return $data;
    }

    /**
     * 2022-12-15
     * @param type $ids
     * @return type
     */
    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    foreach ($lists as &$v) {
                        // 短链地址
                        $v['shortLink'] = self::keyShortLink($v['link_key']);
                    }
                    return $lists;
                });
    }

    /**
     * 2022-12-15:key转短链接
     * @param type $key
     * @return type
     */
    public static function keyShortLink($key) {
        return Request::domain(true) . "/t/" . $key;
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
