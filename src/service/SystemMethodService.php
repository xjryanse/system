<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\user\service\UserAuthUserRoleService;
use xjryanse\user\service\UserAuthRoleMethodService;
use think\facade\Request;
use xjryanse\logic\DbOperate;
use xjryanse\logic\Arrays;
use xjryanse\logic\ThinkPHP;
use xjryanse\logic\DataList;
use Exception;

/**
 * 
 */
class SystemMethodService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemMethod';
    //直接执行后续触发动作
    protected static $directAfter = true;

    /**
     * 2022-12-16
     * @param type $data
     * @param type $uuid
     */
    public static function extraPreSave(&$data, $uuid) {
        if (isset($data['roleIds'])) {
            UserAuthRoleMethodService::methodRoleIdSave($uuid, $data['roleIds']);
        }
    }

    /**
     * 2022-12-16
     * @param type $data
     * @param type $uuid
     */
    public static function extraPreUpdate(&$data, $uuid) {
        if (isset($data['roleIds'])) {
            UserAuthRoleMethodService::methodRoleIdSave($uuid, $data['roleIds']);
        }
    }

    /*
     * 删除关联
     */

    public function extraAfterDelete() {
        UserAuthRoleMethodService::methodClear($this->uuid);
    }

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    $roleArr = UserAuthRoleMethodService::groupBatchSelect('method_id', $ids, 'method_id,role_id');
                    foreach ($lists as &$v) {
                        $v['roleIds'] = array_column(Arrays::value($roleArr, $v['id'], []), 'role_id') ?: [];
                        $v['roleCount'] = count(Arrays::value($roleArr, $v['id'], []));
                    }
                    return $lists;
                });
    }

    /**
     * 获取方法id
     * @return type
     */
    public static function getMethodId() {
        $con[] = ['module', '=', uncamelize(Request::module())];
        $con[] = ['controller', '=', uncamelize(Request::controller())];
        $con[] = ['action', '=', uncamelize(Request::action())];
        $con[] = ['adm_key', '=', Request::param('admKey', '')];
        if (Request::param('table_name')) {
            $con[] = ['table_name', '=', Request::param('table_name')];
        }
        $info = self::staticConFind($con);
        return $info ? $info['id'] : '';
    }

    /**
     * 当前用户请求的当前接口是否具备访问权限
     * @return boolean
     */
    public static function hasAuth() {
        $methodId = self::getMethodId();
        $info = self::getInstance($methodId)->get();
        // 无，不需要权限，未开启，则不校验
        if (!$info || !$info['auth_check'] || !$info['status']) {
            return true;
        }
        $userId = session(SESSION_USER_ID);
        $roleIds = UserAuthUserRoleService::userRoleIds($userId);
        $roleMethodIds = UserAuthRoleMethodService::roleMethodIds($roleIds);
        // 所请求方法id,在用户所有角色提取的有权限方法id中，则有权限
        return in_array($methodId, $roleMethodIds);
    }

    /**
     * 2022-12-16
     * @param type $module
     * @param type $controller
     * @param type $action
     * @param type $admKey
     * @return type
     */
    public static function getMethodKey($module, $controller, $action, $admKey) {
        $string = $module . '_' . $controller . '_' . $action . '_' . $admKey;
        return sha1($string);
    }

    /**
     * 方法key存在
     * @param type $methodKey
     * @return type
     */
    public static function hasMethodByKey($methodKey) {
        $con[] = ['methodKey', '=', $methodKey];
        return self::staticConFind($con) ? true : false;
    }

    /**
     * 提取表的默认key
     * @param type $tableName
     * @param type $method
     */
    protected static function tableDefaultKey($tableName, $method) {
        $controller = DbOperate::getController($tableName);
        $tableKey = DbOperate::getTableKey($tableName);
        return self::getMethodKey('admin', $controller, $method, $tableKey);
    }

    /**
     * 默认添加
     * @param type $tableName
     * @return type
     */
    public static function defaultAddKey($tableName) {
        return self::tableDefaultKey($tableName, 'add');
    }

    /**
     * 默认列表
     * @param type $tableName
     * @return type
     */
    public static function defaultListKey($tableName) {
        return self::tableDefaultKey($tableName, 'list');
    }

    /**
     * 默认详情
     * @param type $tableName
     * @return type
     */
    public static function defaultGetKey($tableName) {
        return self::tableDefaultKey($tableName, 'get');
    }

    /**
     * 默认更新
     * @param type $tableName
     * @return type
     */
    public static function defaultUpdateKey($tableName) {
        return self::tableDefaultKey($tableName, 'update');
    }

    /**
     * 默认删除
     * @param type $tableName
     * @return type
     */
    public static function defaultDelKey($tableName) {
        return self::tableDefaultKey($tableName, 'del');
    }

    /**
     * 数据表生成默认方法
     * @param type $tableName
     * @param type $method
     */
    protected static function tableGenerate($tableName, $method) {
        if (!$tableName) {
            throw new Exception('数据表必须');
        }
        $module = 'admin';
        $controller = DbOperate::getController($tableName);
        $tableKey = DbOperate::getTableKey($tableName);

        $name = SystemColumnService::tableNameGetName($tableName);
        $data['describe'] = $name ? $name . '-' . $method : $tableName . '-' . $method;
        $data['module'] = 'admin';
        $data['controller'] = $controller;
        $data['action'] = $method;
        $data['adm_key'] = $tableKey;

        $methodKey = self::getMethodKey($module, $controller, $method, $tableKey);
        if (self::hasMethodByKey($methodKey)) {
            throw new Exception('接口已存在' . $methodKey);
        }
        return self::save($data);
    }

    /**
     * 2022-12-16：生成列表接口
     * @param type $tableName
     */
    public static function generateList($tableName) {
        return self::tableGenerate($tableName, 'list');
    }

    public static function generateAdd($tableName) {
        return self::tableGenerate($tableName, 'add');
    }

    public static function generateUpdate($tableName) {
        return self::tableGenerate($tableName, 'update');
    }

    public static function generateGet($tableName) {
        return self::tableGenerate($tableName, 'get');
    }

    public static function generateDel($tableName) {
        return self::tableGenerate($tableName, 'del');
    }

    /*     * **** 提取系统基本数据 ***** */

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
     * 描述
     */
    public function fDescribe() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 模块
     */
    public function fModule() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 控制器
     */
    public function fController() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 方法
     */
    public function fAction() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 表键
     */
    public function fAdmKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    public function fTableName() {
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
