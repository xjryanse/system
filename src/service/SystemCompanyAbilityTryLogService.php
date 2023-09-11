<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;
use Exception;

/**
 * 跨系统应用信息
 */
class SystemCompanyAbilityTryLogService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

// 静态模型：配置式数据表
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemCompanyAbilityTryLog';
    
    public static function extraPreSave(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }

    public static function extraPreUpdate(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }

    public function extraPreDelete() {
        self::stopUse(__METHOD__);
    }
    
    public static function ramPreSave(&$data, $uuid) {
        $companyAbilityId           = Arrays::value($data, 'company_ability_id');
        $compAbilityInfo            = SystemCompanyAbilityService::getInstance($companyAbilityId)->get();
        $data['ability_id']         = Arrays::value($compAbilityInfo, 'ability_id');
        $data['bind_company_id']    = Arrays::value($compAbilityInfo, 'bind_company_id');
        return $data;
    }
    
    public static function ramAfterSave(&$data, $uuid) {
        $companyAbilityId = Arrays::value($data, 'company_ability_id');
        SystemCompanyAbilityService::getInstance($companyAbilityId)->updateDataRam();
    }
    /*
     * 20230806:
     */
    public static function ramAfterUpdate($data, $uuid) {
        $info = self::getInstance($uuid)->get();
        $companyAbilityId = Arrays::value($info, 'company_ability_id');
        SystemCompanyAbilityService::getInstance($companyAbilityId)->updateDataRam();
    }
    /*
     * 20230806:
     */
    public function ramAfterDelete($data) {
        $companyAbilityId = Arrays::value($data, 'company_ability_id');
        SystemCompanyAbilityService::getInstance($companyAbilityId)->updateDataRam();
    }


}
