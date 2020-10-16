<?php
namespace xjryanse\system\interfaces;

/**
 * 有关联主表的接口
 */
interface MainModelInterface
{
    /**
     * 数据列表
     * @param type $con
     */
    public static function lists( $con = [],$order='');
    /**
     * 主模型
     */
    public static function mainModel();
    /**
     * 新增保存
     */
    public static function save( array $data);    
    /**
     * 主表信息
     * @param type $cache   缓存时间
     */
    public function get( $cache = 5);
    /**
     * 主表及相关连的表信息
     */
    public function info( $cache = 5 );
    /**
     * 更新项目
     */
    public function update(  array $data );
    /**
     * 删除项目
     */
    public function delete();

}
