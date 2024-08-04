<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;

use xjryanse\logic\Arrays;
use xjryanse\logic\Debug;
/**
 * 导入模板
 */
class SystemImportTemplateService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\StaticModelTrait;
    use \xjryanse\traits\ObjectAttrTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemImportTemplate';

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function ($lists) use ($ids) {
                    return $lists;
                }, true);
    }
    
    /**
     * 下载模板
     * 20231228
     */
    public static function doTplDownload($param){

        $key    = Arrays::value($param, 'importKey');
        $id     = self::keyToId($key);
        $info   = self::getInstance($id)->get();

        $data['fileName']   = $info['template_name'].'.xlsx';
        $data['url']        = $info['file_id']['file_path'];
        return $data;
    }
    
    public static function keyToId($key){
        $con[] = ['key','=',$key];
        $info = self::staticConFind($con);
        return $info ? $info['id'] : '';
    }
    /**
     * 带
     * @param type $param
     * @return bool
     */
    public function doImportData($param){
        $templateId = $this->uuid;
        $commFields = SystemImportTemplateCommFieldsService::getFieldsCov($templateId);
        // 公共的数据，需要拼到列表去
        $commData   = Arrays::keyReplace($param, $commFields);
        // 列表的数据
        $tableData  = Arrays::value($param, 'table_data') ? : [];
        // [处理]公共数据拼到列表数据
        // 先提取一下处理逻辑：
        $dealList = SystemImportTemplateDealService::dealList($templateId);
        foreach($tableData as &$v){
            $v = array_merge($commData, $v);
            // 写入基础的列表
            foreach($dealList as $d){
                $respField = Arrays::value($d, 'resp_field');
                // 例如，bus_id
                $v[$respField] = SystemImportTemplateDealService::getInstance($d['id'])->dealOperate($v);
                // Debug::dump($d['table_name']);
                // Debug::dump($v);
            }
            // Debug::dump('一条结束');
        }

        return true;
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
     *
     */
    public function fTemplateName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fFileId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 对应数据表名
     */
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
