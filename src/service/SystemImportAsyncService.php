<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\system\service\SystemErrorLogService;
use xjryanse\system\logic\ImportLogic;

/**
 * 导入模板
 */
class SystemImportAsyncService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemImportAsync';

    /**
     * 获取待执行任务列表
     */
    public static function getTodos() {
        $con[] = ['op_status', '=', XJRYANSE_OP_TODO];
        return self::lists($con);
    }

    public function doImport() {
        $info = $this->get();
        //发模板
        if (!$info['table_name']) {
            $this->setRespMessage('导入失败，table_name必须');
            return false;
        }
        //更新为进行中
        $this->update(['op_status' => XJRYANSE_OP_DOING]);
        try {
            $preData = json_decode($info['pre_data'], JSON_UNESCAPED_UNICODE);
            $preData['app_id'] = $info['app_id'];
            $preData['company_id'] = $info['company_id'];
            $preData['source'] = $info['source'];
            $preData['creater'] = $info['creater'];
            $preData['updater'] = $info['creater'];

            //执行导入到表方法
            $res = ImportLogic::doImport($info['table_name']
                            , $info['file_id']
                            , json_decode($info['headers'], JSON_UNESCAPED_UNICODE)
                            , $preData
                            , json_decode($info['cov_data'], JSON_UNESCAPED_UNICODE)
            );

            //更新为已完成
            $this->update(['op_status' => XJRYANSE_OP_FINISH, 'resp_message' => '数据导入成功' . $res . '条']);
        } catch (\Exception $e) {
            //记录错误
            SystemErrorLogService::exceptionLog($e);
            $data['resp_message'] = $e->getMessage();
            $data['op_status'] = XJRYANSE_OP_FAIL;
            $this->update($data);
        }
        //导入
    }

    /**
     * 添加导入任务
     * @param type $tableName       表名
     * @param type $fileId          文件id
     * @param array $headers        匹配头
     * @param array $preInputData   预写数据
     * @param type $data            额外数据
     */
    public static function addTask($tableName, $fileId, array $headers, array $preInputData, $data = []) {
        $preInputData['company_id'] = session(SESSION_COMPANY_ID);

        $data['table_name'] = $tableName;
        $data['file_id'] = $fileId;
        $data['headers'] = json_encode($headers, JSON_UNESCAPED_UNICODE);
        $data['pre_data'] = json_encode($preInputData, JSON_UNESCAPED_UNICODE);
        $data['table_name'] = $tableName;

        return self::save($data);
    }

    public function setRespMessage($message) {
        $data['resp_message'] = $message;
        return $this->update($data);
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
