<?php

namespace xjryanse\system\service\company;

/**
 * 分页复用列表
 */
trait FieldTraits{

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
     * 公司名称
     */
    public function fName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    public function fBindCustomerId() {
        return $this->getFFieldValue(__FUNCTION__);
    }
    
    public function fLogo() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 地区码
     * @return type
     */
    public function fAreaCode() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公司编号
     */
    public function fCompanyNo() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公司简称
     */
    public function fShortName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公司类型
     */
    public function fCompanyCate() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 成立时间
     */
    public function fLaunchTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 经营范围
     */
    public function fManagementContent() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 省
     */
    public function fProvince() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 市
     */
    public function fCity() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 县
     */
    public function fDistrict() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公司地址
     */
    public function fAddress() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 营业执照编号
     */
    public function fLicence() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 法人姓名
     */
    public function fFrName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 法人手机
     */
    public function fFrMobile() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 接口访问key
     */
    public function fKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 小程序appid
     */
    public function fWeAppId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 公众号acid
     */
    public function fWePubId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    public function fDevProjectId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 创建公司
     */
    public function fCreateCompanyId() {
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
     * 创建者
     */
    public function fCreater() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 更新者
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
