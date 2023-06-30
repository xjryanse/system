<?php
namespace xjryanse\system\model;

/**
 * 轮播列表
 */
class SystemBannerList extends Base
{
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        [
            'field'     =>'group_id',
            // 去除prefix的表名
            'uni_name'  =>'system_banner_group',
            'uni_field' =>'id',
            'in_list'   => true,
            'in_statics'=> true,
            'in_exist'  => true,
            'del_check' => true,
            'del_msg'   => '该分组有轮播明细，请先删除'
        ],
    ];

    public static $picFields = ['banner_img'];

    /**
     * 用户头像图标
     * @param type $value
     * @return type
     */
    public function getBannerImgAttr($value) {
        return self::getImgVal($value);
    }

    /**
     * 图片修改器，图片带id只取id
     * @param type $value
     * @throws \Exception
     */
    public function setBannerImgAttr($value) {
        return self::setImgVal($value);
    }
    
}