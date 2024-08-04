<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;
use xjryanse\logic\DbOperate;
use xjryanse\logic\Strings;
use xjryanse\logic\Debug;
use Exception;
/**
 * 导入模板
 */
class SystemImportTemplateDealService extends Base implements MainModelInterface {

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
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemImportTemplateDeal';

    /**
     * 20240101：处理列表
     */
    public static function dealList($templateId){
        $con[] = ['template_id','=',$templateId];
        return self::staticConList($con,'','level');
    }
    
    /**
     * 20240101：封装处理逻辑
     */
    public function dealOperate($param){
        $info       = $this->get();
        // 数据表
        $tableName  = Arrays::value($info, 'table_name');
        // 20240101:固定项
        $fixedData = Arrays::value($info, 'fixed_data');
        if($fixedData){
            $fData = json_decode($fixedData, JSON_UNESCAPED_UNICODE);
            $param = array_merge($param, $fData);
        }
        // 提取处理字段列表
        $fields         = SystemImportTemplateDealFieldsService::getFieldsCov($this->uuid);
        // 提取保存的数据
        $saveData       = Arrays::keyReplace($param, $fields);
        // 唯一值，取id
        $uniqueFields   = SystemImportTemplateDealFieldsService::getUniqueFieldsCov($this->uuid);
        // 唯一值
        $uniqueData     = Arrays::keyReplace($param, $uniqueFields);
        
        $service        = DbOperate::getService($tableName);
        //【无唯一数据，直接添加】
        if(!$uniqueData){
            if($saveData){
                return $service::saveGetIdRam($saveData);
            }
        }
        //【有唯一数据】
        // 20240103:增加只查的逻辑(驾驶员导入时捆绑车牌)
        $onlyQuery      = Arrays::value($info, 'only_query');
        //【非查询的添加】
        if(!$onlyQuery){
            $id = $service::commGetIdEG($uniqueData);
            // 20240307:反馈导入司机异常,增加更新动作
            $service::getInstance($id)->doUpdateRam($saveData);
            return $id;
        }
        //【只有查询】
        $id = $service::commGetId($uniqueData);
        if(!$id){
            $errMsg      = Arrays::value($info, 'err_msg') ? : '记录不存在'.json_encode($uniqueData, true);
            throw new Exception(Strings::dataReplace($errMsg, $uniqueData));
        }
        //Debug::dump($service);
        //Debug::dump($id);
        return $id;
    }

}
