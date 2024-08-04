<?php

namespace xjryanse\system\service\companyApply;

use xjryanse\system\service\SystemCompanyService;
use xjryanse\logic\Arrays;
use Exception;
/**
 * 字段复用列表
 */
trait DoTraits{

    /**
     * 端口初始化
     * 20231117
     */
    public function doCompanyInit(){
        $info = $this->get();
        if($info['new_company_id']){
            throw new Exception('端口已经开通过了，编号'.$info['new_company_id']);
        }

        $compName           = Arrays::value($info, 'comp_name');
        $data               = [];
        $data['short_name'] = $compName;
        $data['fr_name']    = Arrays::value($info, 'realname');
        $data['fr_mobile']  = Arrays::value($info, 'phone');
        $data['cate']       = Arrays::value($info, 'cate');
        // 待激活
        $data['is_active']  = 0;

        $res                = SystemCompanyService::init($compName, $data);

        $uData                      = [];
        $uData['audit_status']      = 1;
        $uData['new_company_id']    = $res['id'];

        $this->updateRam($uData);

        return $res;
    }
    /**
     * 带类型的发起申请
     * @param type $param
     */
    public static function doApplyWithCate($param){
        $sData          = Arrays::value($param, 'table_data');
        $sData['cate']  = Arrays::value($param, 'cate');
        
        return self::saveRam($sData);
    }
    
}
