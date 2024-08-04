<?php

namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Debug;
use think\facade\Request;
use think\Container;
use think\File as tpFile;
use xjryanse\logic\Arrays;
use xjryanse\logic\Arrays2d;
use xjryanse\logic\DataCheck;
use xjryanse\logic\DbOperate;
use xjryanse\logic\ImgCompress;
use xjryanse\logic\Oss;
use xjryanse\logic\Url;
use xjryanse\logic\File;
use xjryanse\curl\Query;
use xjryanse\system\logic\ConfigLogic;
use Exception;

/**
 * 上传附件
 */
class SystemFileService implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;


    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\system\\model\\SystemFile';
    protected static $getCache = true;
    
    use \xjryanse\system\service\file\FieldTraits;
    use \xjryanse\system\service\file\MigTraits;

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use($ids) {
                    // 获取使用记录数
                    $useCountArr = SystemFileUseService::groupBatchCount('file_id', $ids);
                    foreach ($lists as &$v) {
                        //使用数?
                        $v['useCount'] = Arrays::value($useCountArr, $v['id'], 0);
                        // 20230525:完整的路径
                        $v['fullPath'] = self::mainModel()->getFullPath($v['file_path']);
                        
                        // 提取异常结果
                        $hasLocalArr          = self::calcHasLocal($v['file_path'], $v);
                        $v = array_merge($v, $hasLocalArr);
                    }
                    
                    // 20240713批量提交：is_error字段的更新
                    DbOperate::dealGlobal();
                    return $lists;
                });
    }
    /**
     * 计算是否有本地文件
     * @param type $filePath
     * @param type $data
     * @return type
     */
    protected static function calcHasLocal($filePath, $data = []){
        // dump($data);
        // sql查询结果
        $v = [];
        $v['hasLocal']    = file_exists($filePath) ? 1 : 0;
        if($v['hasLocal'] != Arrays::value($data, 'has_local')){
            // 更新
            $id = Arrays::value($data, 'id');
            self::getInstance($id)->updateRam(['has_local'=>$v['hasLocal']]);
        }

        return $v;
    }

//    public static function extraAfterSave(&$data, $uuid) {
//        //self::getInstance($uuid)->doBase64Brief();
//    }

    /**
     * 删除文件，并删除记录
     * @param type $onlyJustUpload  是否仅删除刚上传的
     * @return boolean
     */
    public function unlink($onlyJustUpload = true) {
        // 如果仅删除刚上传的,而文件不是刚上传，20秒判断20220315
        if ($onlyJustUpload && !$this->isJustUpload()) {
            return false;
        }

        $info = self::mainModel()->where('id', $this->uuid)->field('id,is_lock,file_path as pathRaw')->find();
        if ($info['is_lock']) {
            return false;
        }
        $basePath = Arrays::value($_SERVER, 'DOCUMENT_ROOT');
        $filePathFull = $basePath . '/' . $info['pathRaw'];
        if ($info['pathRaw'] && file_exists($filePathFull)) {
            //删除服务器上的文件
            $res = unlink($filePathFull);
            if (!file_exists($filePathFull)) {
                //文件不存在，则删除路径表的信息记录
                $this->delete();
            }
        }
        return $res;
    }

    /**
     * 文件类上传
     */
    public static function uplFile($name) {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($name);
        if (!$file) {
            // 上传失败获取错误信息
            throw new Exception('未上传文件');
        }
        //查询文件
        $isFileExist = self::isFileExist($file->md5(), $file->sha1());
        if ($isFileExist) {
            //文件已存在
            return $isFileExist;
        }
        $data['file_size'] = $file->getSize();

        // 移动到框架应用根目录/v/Uploads/files/ 目录下
        $info = $file->validate(['ext' => 'jpg,jpeg,png,gif,bmp,tiff,pdf,apk,wgt,ipa,doc,docx,xls,xlsx,txt,zip,rar,ppt,et,zip,rar'])->move('./Upload/files');

        if ($info) {
            $path = 'Upload/files/' . $info->getSaveName();
            $data['file_type'] = File::getFileType($path);

            return self::uplSave($info, $path, $data);
        } else {
            // 上传失败获取错误信息
            throw new Exception($file->getError());
        }
    }

    /**
     * 图片类上传
     */
    public static function uplPic($name, $subType = 'other') {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($name);
        if (!$file && Request::param($name, '')) {
            //获取base64的编码
            return self::drawPic(Request::param($name, ''));
        }
        if (!$file) {
            // 上传失败获取错误信息
            throw new Exception('未上传文件');
        }
        //查询文件
        $isFileExist = self::isFileExist($file->md5(), $file->sha1());
        //20210406，增加判断文件夹路径下是否真实存在
        $basePath = Arrays::value($_SERVER, 'DOCUMENT_ROOT');
        $filePathFull = $basePath . '/' . Arrays::value($isFileExist, 'pathRaw');
        if ($isFileExist && file_exists($filePathFull)) {
            //文件已存在
            return $isFileExist;
        }
        $data['file_size'] = $file->getSize();
        //大小限制20M 20971520
        if ($file->getSize() >= 10485760) {
            throw new Exception('上传大小限制10M内');
        }

        // 移动到框架应用根目录/v/Uploads/Picture/ 目录下
        $info = $file->validate(['size' => 20971520, 'ext' => 'jpg,jpeg,png,gif,bmp,tiff,pdf,pcx,avi,mov,rmvb,rm,asf,wma,flv,mpg,mkv,mp3,mp4,doc,docx,xls,xlsx,zip,rar'])->move('./images');
        if ($info) {
            $path = 'images/' . $info->getSaveName();
            $data['file_type'] = File::getFileType($path);
            //二级分类
            $data['sub_type'] = $subType;
            return self::uplSave($info, $path, $data);
        } else {
            // 上传失败获取错误信息
            throw new Exception($file->getError());
        }
    }

    /**
     * 2022-12-10：上传到oss对象存储
     * @param type $name
     * @param type $subType
     * @return type
     * @throws Exception
     */
    public static function uplOss($name, $subType = 'other') {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($name);
        /*
          if (!$file && Request::param($name, '')) {
          //获取base64的编码
          return self::drawPic(Request::param($name, ''));
          }
         */
        if (!$file) {
            // 上传失败获取错误信息
            throw new Exception('未上传文件');
        }
        //查询文件
        $isFileExist = self::isFileExist($file->md5(), $file->sha1());
        //20210406，增加判断文件夹路径下是否真实存在
        $basePath = Arrays::value($_SERVER, 'DOCUMENT_ROOT');
        $filePathFull = $basePath . '/' . Arrays::value($isFileExist, 'pathRaw');
        if ($isFileExist && file_exists($filePathFull)) {
            //文件已存在
            return $isFileExist;
        }
        $data['file_size'] = $file->getSize();
        //大小限制20M 20971520
        if ($file->getSize() >= 10485760) {
            throw new Exception('上传大小限制10M内');
        }

        // 移动到框架应用根目录/v/Uploads/Picture/ 目录下
        $info = $file->validate(['size' => 20971520, 'ext' => 'jpg,jpeg,png,gif,bmp,tiff,pdf,pcx,avi,mov,rmvb,rm,asf,wma,flv,mpg,mkv,mp3,mp4,doc,docx,xls,xlsx,zip,rar']);
        if ($info) {
            // $path = 'images/' . $info->getSaveName();
            //二级分类
            $data['sub_type'] = $subType;

            $rawName = $file->getInfo()['name'];
            $savePath = 'images/' . self::autoSavePath() . '.' . self::autoSaveExt($rawName);
            $data['file_type'] = File::getFileType($savePath);

            $res = Oss::getInstance()->uploadFile($savePath, $file->getInfo()['tmp_name']);
            Debug::debug('上传oss返回结果', $res);
            return self::uplSave($info, $savePath, $data);
        } else {
            // 上传失败获取错误信息
            throw new Exception($file->getError());
        }
    }

    //将base64格式图片存到服务区
    private static function drawPic($pic_url) {
        $base64_img = str_replace(' ', '+', $pic_url);
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result)) {
            if ($result[2] == 'jpeg') {
                $image_name = uniqid() . '.jpg';
            } else {
                $image_name = uniqid() . '.' . $result[2];
            }
            $fileName = "image/" . date('Ymd') . "/{$image_name}";
            $basePath = Container::get('app')->getRootPath();
            $pathName = $basePath . 'public' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . date('Ymd') . DIRECTORY_SEPARATOR;
            if (!is_dir($pathName)) {
                mkdir($pathName, 0777, true);
            }
            $savePath = "./" . $fileName;
            if (file_put_contents($savePath, base64_decode(str_replace($result[1], '', $base64_img)))) {

                $tpFile = new tpFile($savePath);
                $data['file_type'] = File::getFileType($savePath);
                //文件信息存数据库;移除点
                return self::uplSave($tpFile, ltrim($savePath, './'), $data);
            } else {
                return false;
            }

            return base64_decode(str_replace($result[1], '', $base64_img));
        }
        return false;
    }

    /**
     * 查询文件是否存在
     * 根据md5值和sha1值校验
     */
    public static function isFileExist($md5, $sha1) {
        if (!$md5 || !$sha1) {
            return false;
        }
        $con[] = ['md5', '=', $md5];
        $con[] = ['sha1', '=', $sha1];
        $res = self::mainModel()->where($con)->field('id,is_lock,file_path,file_path as pathRaw')->find();
        return $res;
    }

    /**
     * 是否刚上传的文件，一般用于删除前进行判断
     */
    public function isJustUpload() {
        $info = $this->get();
        return $info['create_time'] ? time() - strtotime($info['create_time']) < 20 : false;
    }

    /**
     * 上传信息保存
     * @param type $info    文件信息
     * @param type $path    保存路径
     * @param type $data    数据
     * @return type
     */
    public static function uplSave($info, $path = '', $data = []) {
        $data['file_path'] = str_replace('\\', '/', $path);
        $data['md5'] = $info->md5();
        $data['sha1'] = $info->sha1();
        $data['status'] = '1';
        if ($data['md5'] && $data['sha1']) {
            $con[] = ['md5', '=', $data['md5']];
            $con[] = ['sha1', '=', $data['sha1']];
            $info = self::find($con);
            if ($info) {
                return self::getInstance($info['id'])->update($data);
            }
        }

        return self::save($data);
    }

    /**
     * 保存路径返回id
     */
    public static function pathSaveGetId($path, $data = []) {
        $con[] = ['file_path', '=', $path];
        Debug::debug('pathSaveGetId的查询$con', $con);
        $info = self::find($con);
        if (!$info) {
            $data['file_path'] = $path;
            $info = self::save($data);
        }
        return $info['id'];
    }

    /**
     * 生成base64缩略预览图
     */
    public function doBase64Brief() {
        $info = self::mainModel()->where('id', $this->uuid)->field('id,file_type,base64_brief,file_path as pathRaw')->find();
        //已有缩略，或不是图片
        if ($info['base64_brief'] || ($info['file_type'] != 'image' && $info['file_type'] != 'images')) {
            return false;
        }
        $basePath = Arrays::value($_SERVER, 'DOCUMENT_ROOT');
        $source = $basePath . '/' . $info['pathRaw'];
        // 这个文件
        $imageInfo = getimagesize($source);
        //缩放到宽度为150
        $percent = round(150 / $imageInfo[0], 2);
        //从输出缓冲区中提取数据
        ob_start();
        (new ImgCompress($source, $percent))->compressImg();
        $imageCode = ob_get_contents();
        ob_end_clean();
        $base64_image = 'data:' . $imageInfo['mime'] . ';base64,' . chunk_split(base64_encode($imageCode));
        return $this->update(['base64_brief' => $base64_image]);
    }

    /**
     * 获取自动保存的文件名
     * @return type
     */
    protected static function autoSavePath() {
        return date('Ymd') . DIRECTORY_SEPARATOR . md5(microtime(true));
    }

    /**
     * 获取扩展名
     * @return type
     */
    protected static function autoSaveExt($rawName) {
        return pathinfo($rawName, PATHINFO_EXTENSION);
    }
    /**
     * 计算文件类型
     * @createTime 2023-06-18 13:46:00
     * @creater 土拨鼠
     */
    public static function calFileType($path) {
        $pathInfo   = pathinfo($path, PATHINFO_EXTENSION);
        $imageArr   = ['jpg','png','jpeg','gif','bmp','tiff'];
        $videoArr   = ['mp4','avi','mov','rmvb','flv'];
        $fileArr    = ['doc','docx','xls','xlsx','zip','rar'];
        $typeArr    = array_merge(array_fill_keys($imageArr, 'image'),array_fill_keys($videoArr, 'video'),array_fill_keys($fileArr, 'file'));

        return Arrays::value($typeArr, $pathInfo,'other');
    }

    /**
     * 20230516：带远端的提取文件
     */
    public static function filesWithSys($ids) {
        self::queryCountCheck(__METHOD__);
        if (!$ids) {
            return [];
        }
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }
        $ids = array_unique($ids);
        //20230516：先从本地提取：
        $lists = self::files($ids);
        //【远端】本地如果没有，从远端提取；
        // 1-已有id
        $hasIds = array_unique(array_column($lists, 'id'));
        // 2-没有的id
        $noIds = array_diff($ids, $hasIds);
        // [远端提取]
        if ($noIds) {
            $remoteList = self::filesRemote($noIds);
            $lists = array_merge($lists, $remoteList);
        }

        return $lists;
    }

    /**
     * 20230516:提取文件信息
     */
    public static function files($ids) {
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }
        // 20230516:去空，不查
        $ids = Arrays::unsetEmpty($ids);
        if (DataCheck::isEmpty($ids)) {
            return [];
        }
        // 步骤1：有缓存的先提取一遍；
        $dataArr = [];
        foreach ($ids as $k => $id) {
            $info = self::getInstance($id)->getFromCache();
            // 20230522????? array_intersect_key(): Argument #1 is not an array
            if (is_object($info)) {
                $info = $info->toArray();
            }
            // 一个不保险，空对象不过滤
            if ($info && $info['id']) {
                $dataArr[] = $info;
            }
            // 20230516: 已查询，即使为空也不再查
            $hasQuery = self::getInstance($id)->hasUuDataQuery;
            if ($info || $hasQuery) {
                unset($ids[$k]);
            }
        }
        // 步骤2：没缓存的查询数据库一遍；
        if ($ids) {
            $dataRes = self::commExtraDetails($ids);
            if ($dataRes) {
                $dataArr = array_merge($dataArr, $dataRes);
            }
        }
        // 步骤3：拼接数据：TODO??
        foreach ($dataArr as &$v) {
            $v['rawPath'] = $v['file_path'];
            $v['file_path'] = self::mainModel()->getFullPath($v['file_path']);
        }
        // 步骤3：数据库中没有的，查远端一遍；
        return Arrays2d::getByKeys($dataArr, ['id', 'file_type', 'file_path', 'rawPath']);
    }

    /*
     * 20230516:个别系统表对应的模型类，只能从数据库取，且需要绕开系统关联类库，不然会造成死循环
     * 登录接口调不通
     */
    public static function filesDb($ids) {
        $con[] = ['id', 'in', $ids];
        $res = SystemFileService::mainModel()->where($con)->field('id,file_type,file_path')->cache(86400)->select();

        foreach ($res as &$v) {
            $v['rawPath'] = $v['file_path'];
            $v['file_path'] = self::mainModel()->getFullPath($v['file_path']);
        }

        return $res ? $res->toArray() : [];
    }

    /**
     * 20230516:远端配置
     * @return boolean
     * @throws Exception
     */
    public static function filesRemote($idsRaw) {
        $ids = Arrays::unsetEmpty($idsRaw);
        // $baseHost   = ConfigLogic::config('fileBaseHost');
        // 会死循环，替换下面的方法
        $baseHost = SystemConfigsService::getDbKeyValue('fileBaseHost');
        if (!$baseHost || !$ids) {
            return [];
        }

        $url = $baseHost . '/' . session(SESSION_COMPANY_KEY) . '/file/api/files';
        $param['id'] = implode(',', $ids);
        $finalUrl = Url::addParam($url, $param);

        $res = Query::get($finalUrl);
        if ($res['code'] == 0) {
            return $res['data'];
        } else {
            return [];
            // throw new Exception('文件基本站异常：'.$res['message']);
        }
    }

    /*     * **** */


}
