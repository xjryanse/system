<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use think\facade\Request;
use xjryanse\logic\Cachex;
use xjryanse\logic\Arrays2d;
use xjryanse\logic\Arrays;
/**
 * 服务类方法调用日志
 */
class SystemServiceMethodLogService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;
    use \xjryanse\traits\RedisModelTrait;
    use \xjryanse\traits\LogModelTrait;
    
    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemServiceMethodLog';

    use \xjryanse\system\service\serviceMethodLog\PaginateTraits;
    use \xjryanse\system\service\serviceMethodLog\ListTraits;
    
    /**
     * 记录请求日志
     * @param type $service     服务类
     * @param type $method      类方法
     * @param type $param       请求参数
     * @param type $response    返回结果
     * @return type
     */
    public static function log($service, $method, $param, $response, $data = []) {
        global $glQuid;
        $data['caller_class']   = addslashes('\\'.Arrays::value($data, 'caller_class'));
        $data['caller_method']  = Arrays::value($data, 'caller_method');
        $data['uniqid']     = $glQuid;
        $data['url']        = Request::url();
        $data['ip']         = Request::ip();
        $data['service']    = addslashes('\\'.$service);
        $data['method']     = $method;
        $data['param']      = json_encode($param,JSON_UNESCAPED_UNICODE);
        $data['response']   = json_encode($response,JSON_UNESCAPED_UNICODE);
        $data['source']     = session(SESSION_SOURCE);
        $data['creater']    = session(SESSION_USER_ID);
        // 20230621
        // return self::save($data);
        return self::redisLog($data);
    }
    /**
     * 根据uniqid更新pid
     */
    public static function updatePidByUniqid($uniqid){
        //提取id大于当前，且sort 小于当前的，按sort排序第一条
        $con[] = ['uniqid','=',$uniqid];
        $lists = self::where($con)->select();
        foreach($lists as $v){
            $cone = [];
            $cone[] = ['id','>',$v['id']];
            $cone[] = ['sort','<',$v['sort']];
            $pid = self::where($cone)->order('sort desc')->value('id');
            self::getInstance($v['id'])->update(['pid'=>$pid]);
        }
    }
    
    /*********/
    /**
     * 服务类方法统计
     */
    public static function allCount(){
        return Cachex::funcGet(__METHOD__, function(){
            $res = self::where()->group('service,method')->field('service,method,count(1) as number')->select();
            return $res ? $res->toArray() : [];
        },false,60);
    }
    /**
     * 类名+方法名提取统计次数
     * @param type $service 类名
     * @param type $method  方法名
     * @return type
     */
    public static function serviceMethodCount($service, $method){
        $arr = self::allCount();
        
        $con[] = ['service','=',$service];
        $con[] = ['method','=',$method];
        $info = Arrays2d::listFind($arr, $con);
        return Arrays::value($info, 'number', 0);
    }
}
