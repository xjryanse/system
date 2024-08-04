<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Cachex;
use think\facade\Cache;

/**
 * 行政区划信息
 */
class SystemAreaService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;


    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemArea';

    /**
     * 有赞省市区列表
     */
    public static function vantAreaList() {
        return Cachex::funcGet(__CLASS__ . '_' . __METHOD__, function() {
                    //省
                    $provinceList = self::mainModel()->where('level', 1)->column('area_name', 'area_code');
                    //市
                    $cityList = self::mainModel()->where('level', 2)->column('area_name', 'area_code');
                    //县
                    $countyList = self::mainModel()->where('level', 3)->column('area_name', 'area_code');
                    return ['province_list' => $provinceList, 'city_list' => $cityList, 'county_list' => $countyList];
                });
    }

    /**
     * 行政编码取省市县数组
     * @param type $areaCode
     */
    public static function areaCodeGetDataArr($areaCode) {
        $areaLists = self::vantAreaList();
        //省
        $data['province'] = $areaLists['province_list'][substr($areaCode, 0, 2) . '0000'];
        //市
        $data['city'] = $areaLists['city_list'][substr($areaCode, 0, 4) . '00'];
        //县
        $data['county'] = $areaLists['county_list'][$areaCode];

        return $data;
    }

    /*     * *************************************** */

    /**
     *
     */
    public function fId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 应用名称
     */
    public function fName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 应用id
     */
    public function fAppid() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 应用密钥
     */
    public function fSecret() {
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
