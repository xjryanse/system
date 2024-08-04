<?php
namespace xjryanse\system\logic;

use think\facade\Cache;
use xjryanse\logic\Debug;
use xjryanse\logic\Arrays2d;
use xjryanse\uniform\service\UniformTableFieldService;
/**
 * 数据表逻辑：实体表 + 虚拟表
 */
class TableLogic
{
    protected $table = '';
    
    public function __construct($table) {
        $this->table = $table;
    }
    
    /**
     * 表字段
     */
    public function fieldsArr(){
        $table = $this->table;
        $res = UniformTableFieldService::tableFieldsArr($table);

        $arr = Arrays2d::keyReplace($res, [
            'cus_label'         =>'label'
            ,'field'            =>'field'
            ,'default_type'     =>'type'
            ,'default_option'   =>'option'
        ]);

        return $arr;
    }

}
