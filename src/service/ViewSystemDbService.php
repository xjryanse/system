<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\universal\service\UniversalPageService;
use xjryanse\system\service\SystemColumnListService;
use xjryanse\logic\FrameCode;
use xjryanse\logic\DbOperate;
use xjryanse\logic\Runtime;
/**
 * 
 */
class ViewSystemDbService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;
    use \xjryanse\traits\ObjectAttrTrait;

// 静态模型：配置式数据表
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\ViewSystemDb';
    // 20230619;
    protected static $allServiceArr = []; 

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    // $conT[] = ['table_name', 'in', array_column($lists, 'table_name')];
                    // $ids = SystemColumnService::where($conT)->column('id');
                    // $columnListCount = SystemColumnListService::groupBatchCount('column_id', $ids);
                    foreach ($lists as &$v) {
                        $tableName = $v['table_name'];
                        // 是否有数据表配置
                        $v['columnId']          = SystemColumnService::tableNameGetId($tableName);
                        // $v['columnListCount'] = Arrays::value($columnListCount, $v['columnId']);
                        $conCL                  = [['column_id','=',$v['columnId']]];
                        $v['columnListCount']   = SystemColumnListService::staticConCount($conCL);

                        // $v['hasColumn']         = $v['columnId'] ? 1 : 0;
                        // 是否有列表页面
                        $listKey                = UniversalPageService::defaultListKey($tableName);
                        $v['pageKeyList']       = $listKey;
                        $v['hasListPage']       = UniversalPageService::getByPageKey($listKey) ? 1 : 0;                        
                        // 是否有添加页面
                        $addKey = UniversalPageService::defaultAddKey($tableName);
                        $v['hasAddPage'] = UniversalPageService::getByPageKey($addKey) ? 1 : 0;
                        $v['pageKeyAdd'] = $addKey;
                        // 20230325 是否有详情页面
                        $detailKey = UniversalPageService::defaultDetailKey($tableName);
                        $v['hasDetailPage'] = UniversalPageService::getByPageKey($detailKey) ? 1 : 0;
                        $v['pageKeyDetail'] = $detailKey;

                        // 是否有月统计页面
                        $monthlyStaticsKey = UniversalPageService::defaultMonthlyStaticsKey($tableName);
                        $v['hasMonthlyStaticsPage'] = UniversalPageService::getByPageKey($monthlyStaticsKey) ? 1 : 0;
                        $v['pageKeyMonthlyStatics'] = $monthlyStaticsKey;

                        // 是否有年统计页面
                        $yearlyStaticsKey = UniversalPageService::defaultYearlyStaticsKey($tableName);
                        $v['hasYearlyStaticsPage'] = UniversalPageService::getByPageKey($yearlyStaticsKey) ? 1 : 0;
                        $v['pageKeyYearlyStatics'] = $yearlyStaticsKey;


                        // 20230530：手机列表页
                        $webListKey = UniversalPageService::defaultWebListKey($tableName);
                        $v['pageKeyWebList'] = $webListKey;
                        $v['hasWebListPage'] = UniversalPageService::getByPageKey($webListKey) ? 1 : 0;
                        // 20230530：手机添加页页
                        $webAddKey = UniversalPageService::defaultWebAddKey($tableName);
                        $v['hasWebAddPage'] = UniversalPageService::getByPageKey($webAddKey) ? 1 : 0;
                        $v['pageKeyWebAdd'] = $webAddKey;
                        // 20230530：手机详情页
                        $webDetailKey = UniversalPageService::defaultWebDetailKey($tableName);
                        $v['hasWebDetailPage'] = UniversalPageService::getByPageKey($webDetailKey) ? 1 : 0;
                        $v['pageKeyWebDetail'] = $webDetailKey;
                        // 20230615：统计数据表中的方法数
                        $methodList = self::tableServiceMethodList($tableName);
                        $v['serviceMethodCount'] = count($methodList);                        
                        // 20230728:控制前端显示
                        $v['status'] = 1;
                        
                        // 字段缓存                        
                        $columnCacheFile    = Runtime::tableColumnFileName($tableName);
                        $v['hasColumnFile'] = is_file($columnCacheFile) ? 1 : 0;
                        // 表全量缓存
                        $dataCacheFile    = Runtime::tableFullCacheFileName($tableName);
                        $v['hasDataFile']   = is_file($dataCacheFile) ? 1 : 0;
                    }

                    return $lists;
                },true);
    }
    
    /*
     * 20230615：数据表中有的方法列表
     */
    protected static function tableServiceMethodList($tableName) {
        if(!self::$allServiceArr){
            self::$allServiceArr = FrameCode::servicesArr();
        }

        return array_filter(self::$allServiceArr, function ($var) use ($tableName) {
            return ($var['table'] == $tableName);
        });

        // $con[] = ['table', '=', $tableName];
        // return FrameCode::servicesArr($con);
    }
    /**
     * 重写info方法，拼接建表sql语句
     * @creater 土拨鼠
     * @createTime 2023-6-17 22:06:00
     */
    public function info() {
        $res = self::extraDetails($this->uuid);
        // TODO:需控制只有开发人员权限才显示
        $res['createTableSql'] = DbOperate::createTableSql($res['table_name']);
        return $res;
    }

}
