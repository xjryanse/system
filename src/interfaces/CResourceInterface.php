<?php
namespace xjryanse\system\interfaces;

/**
 * 资源控制器接口
 */
interface CResourceInterface
{
    /**
     * 新增保存
     */
    public function save();    
    /**
     * 更新项目
     */
    public function update();
    /**
     * 删除项目
     */
    public function delete();
    /**
     * 主表信息
     * @param type $cache   缓存时间
     */
    public function get();

}
