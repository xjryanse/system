<?php

namespace xjryanse\system\service\file;

use xjryanse\logic\Debug;
use Exception;
use xjryanse\system\logic\ConfigLogic;
/**
 * 文件迁移复用
 */
trait MigTraits{
    /**
     * 文件迁移
     * @return bool
     * @throws Exception
     */
    public function fileMigrate(){
        if(!Debug::isDevIp()){
            throw new Exception('环境校验失败');
        }
        $file = $this->get();

        $savePath = './'.$file['file_path'];
        if (!file_exists(dirname($savePath)) && !mkdir(dirname($savePath), 0777, true)) {
            throw new Exception('创建目录'. dirname($savePath) .'失败');
        }
        
        $mainHost = ConfigLogic::config('MigrationMainHost');
        $FFile   = file_get_contents( $mainHost.$file['file_path'] );
        //写入本地服务器
        $res = file_put_contents( $savePath, $FFile );
        return $res;
    }
}
