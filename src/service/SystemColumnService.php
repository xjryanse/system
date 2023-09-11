<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\universal\service\UniversalPageService;
use xjryanse\logic\Arrays;
use xjryanse\logic\DbOperate;

/**
 * 数据表
 */
class SystemColumnService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

// 静态模型：配置式数据表
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemColumn';

    use \xjryanse\system\service\column\FieldTraits;
    
    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    $columnListCounts = SystemColumnListService::groupBatchCount('column_id', $ids);
                    // 外键统计
                    $foreignCounts = SystemColumnListForeignService::groupBatchCount('column_id', $ids);

                    foreach ($lists as &$v) {
                        $tableName = $v['table_name'];
                        //字段数
                        $v['columnListCount'] = Arrays::value($columnListCounts, $v['id'], 0);
                        //外键数
                        $v['foreignCount'] = Arrays::value($foreignCounts, $v['id'], 0);
                        // 数据库中是否存在该表
                        $v['hasDb'] = DbOperate::isTableExist($tableName) ? 1 : 0;
                        // 生效的默认服务类名
                        $v['dbService'] = DbOperate::getService($tableName);
                        // 2022-12-15:服务类是否存在
                        $v['isServiceExist'] = class_exists($v['dbService']) ? 1 : 0;

                        // 是否有列表页面
                        $listKey = UniversalPageService::defaultListKey($tableName);
                        $v['pageKeyList'] = $listKey;
                        $v['hasListPage'] = UniversalPageService::getByPageKey($listKey) ? 1 : 0;
                        // 是否有添加页面
                        $addKey = UniversalPageService::defaultAddKey($tableName);
                        $v['hasAddPage'] = UniversalPageService::getByPageKey($addKey) ? 1 : 0;
                        $v['pageKeyAdd'] = $addKey;
                        // 20230325 是否有详情页面
                        $detailKey = UniversalPageService::defaultDetailKey($tableName);
                        $v['hasDetailPage'] = UniversalPageService::getByPageKey($detailKey) ? 1 : 0;
                        $v['pageKeyDetail'] = $detailKey;
                        // 接口 - 添加key
                        $v['methodAddKey'] = SystemMethodService::defaultAddKey($tableName);
                        $v['hasMethodAdd'] = SystemMethodService::hasMethodByKey($v['methodAddKey']) ? 1 : 0;
                        // 接口 - 更新key
                        $v['methodUpdateKey'] = SystemMethodService::defaultUpdateKey($tableName);
                        $v['hasMethodUpdate'] = SystemMethodService::hasMethodByKey($v['methodUpdateKey']) ? 1 : 0;
                        // 接口 - 列表key
                        $v['methodListKey'] = SystemMethodService::defaultListKey($tableName);
                        $v['hasMethodList'] = SystemMethodService::hasMethodByKey($v['methodListKey']) ? 1 : 0;
                        // 接口 - 详情
                        $v['methodGetKey'] = SystemMethodService::defaultGetKey($tableName);
                        $v['hasMethodGet'] = SystemMethodService::hasMethodByKey($v['methodGetKey']) ? 1 : 0;
                        // 接口 - 删除key
                        $v['methodDelKey'] = SystemMethodService::defaultDelKey($tableName);
                        $v['hasMethodDel'] = SystemMethodService::hasMethodByKey($v['methodDelKey']) ? 1 : 0;
                    }

                    return $lists;
                });
    }

    /**
     * 参数取id
     * @param type $controller
     * @param type $tableKey
     * @return type
     */
    public static function paramGetId($controller, $tableKey) {
        $con[] = ['controller', '=', $controller];
        $con[] = ['table_key', '=', $tableKey];
        $info = self::staticConFind($con);
        return $info ? $info['id'] : '';
    }

    /**
     * 表名取id
     */
    public static function tableNameGetId($tableName) {
        // 20230619:优化性能
        if(!self::$staticListsAll){
            self::staticListsAll();
        }
        $dataArr = array_column(self::$staticListsAll, 'id', 'table_name');
        return Arrays::value($dataArr, $tableName);
    }

    /*
     * 2022-12-16
     */

    public static function tableNameGetName($tableName) {
        $con[] = ['table_name', '=', $tableName];
        //$info = self::find($con);
        //20220303优化性能
        $info = self::staticConFind($con);
        return $info ? $info['name'] : '';
    }

    public function extraAfterDelete() {
        self::checkTransaction();
        // 删除关联的
        if (!$this->get(0)) {
            $con[] = ['column_id', '=', $this->uuid];
            // 字段
            SystemColumnListService::mainModel()->where($con)->delete();
            // 数据块
            //SystemColumnBlockService::mainModel()->where($con)->delete();
            // 按钮
            //SystemColumnBtnService::mainModel()->where($con)->delete();
            // 操作
            //SystemColumnOperateService::mainModel()->where($con)->delete();
        }
    }
    /**
     * 20230905：更新是否静态
     */
    public static function updateIsStatic(){
        $tables = self::column('table_name');
        
        $staticTables = [];
        foreach($tables as $tableName){
            $service = DbOperate::getService($tableName);
            if(method_exists($service, 'staticConList')){
                $staticTables[] = $tableName;
            }
        }
        $con = [];
        $con[] = ['table_name','in',$staticTables];
        // 更新1：先清零
        self::mainModel()->where('is_static',1)->update(['is_static'=>0]);
        // 更新2：再写入
        self::mainModel()->where($con)->update(['is_static'=>1]);
        return true;
    }
    /**
     * 更新记录数
     */
    public static function updateRecordCount(){
        $con    = [];
        $con[]  = ['is_static', '=', 1];
        $tables = self::column('table_name', $con);
        foreach($tables as $table){
            $log = Db::table($table);
            
            
        }
    }
    

}
