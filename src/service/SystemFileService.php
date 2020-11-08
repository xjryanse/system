<?php
namespace xjryanse\system\service;

use xjryanse\system\interfaces\MainModelInterface;
use think\facade\Request;
use think\Exception;
/**
 * 上传附件
 */
class SystemFileService implements MainModelInterface
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\system\\model\\SystemFile';

    /**
     * 文件类上传
     */
    public static function uplFile($name){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($name);
        if(!$file){
            // 上传失败获取错误信息
            throw new Exception( '未上传文件');
        }
        //查询文件
        $isFileExist = self::isFileExist($file->md5(), $file->sha1());
        if($isFileExist){
            //文件已存在
            return $isFileExist;
        }
        
        // 移动到框架应用根目录/v/Uploads/files/ 目录下
        $info = $file->validate(['ext'=>'apk,wgt,ipa,doc,docx,xls,xlsx,txt,zip,rar,ppt'])->move( './Upload/files');
        
        if($info){
            $path = '/Upload/files/'.$info->getSaveName();
            $data['file_type'] = 'file';
            
            return self::uplSave($info, $path, $data);
        } else {
            // 上传失败获取错误信息
            throw new Exception( $file->getError() );
        }
    }
    
    /**
     * 图片类上传
     */
    public static function uplPic($name,$subType = 'other'){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($name);
        if(!$file){
            // 上传失败获取错误信息
            throw new Exception( '未上传文件');
        }        
        if(!$file){
            //获取base64的编码
            return self::drawPic(Request::param($name,''));
        }
        //查询文件
        $isFileExist = self::isFileExist($file->md5(), $file->sha1());
        if($isFileExist){
            //文件已存在
            return $isFileExist;
        }
        //大小限制20M
        if($file->getSize() >= 20971520){
            throw new Exception('上传图片大小限制20M内');
        }        
        
        // 移动到框架应用根目录/v/Uploads/Picture/ 目录下
        $info = $file->validate(['size'=>20971520,'ext'=>'jpg,jpeg,png,gif,bmp,tiff,pcx,avi,mov,rmvb,rm,asf,wma,flv,mpg,mkv,mp3,mp4'])->move( './images');
        if($info){
            $path = '/images/'.$info->getSaveName();
            $data['file_type'] = 'image';
            //二级分类
            $data['sub_type'] =  $subType ;
            return self::uplSave($info, $path,$data);            
        } else {
            // 上传失败获取错误信息
            throw new Exception( $file->getError() );
        }
    }
    
    //将base64格式图片存到服务区
    private static function drawPic($pic_url){
        $base64_img = str_replace(' ', '+', $pic_url);
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img,$result)){
            if($result[2] == 'jpeg'){
                $image_name = uniqid().'.jpg';
            }else{
                $image_name = uniqid().'.'.$result[2];
            }
            
            $image_file = "./images/{$image_name}";
            if(file_put_contents($image_file,base64_decode(str_replace($result[1],'', $base64_img)))){
                $data['path'] = '/images/'.$image_name;
                $data['path']           = str_replace('\\', '/', $data['path']);
                $data['status']         = '1';
                $data['create_time']    = time();

                return self::create($data);
            }else{
                return false;
            }
            
            return base64_decode(str_replace($result[1],'', $base64_img));
        }
        return false;
    }    
    
    /**
     * 查询文件是否存在
     * 根据md5值和sha1值校验
     */
    public static function isFileExist($md5,$sha1)
    {
        if(!$md5 || !$sha1){
            return false;
        }
        $con[] = ['md5','=',$md5];
        $con[] = ['sha1','=',$sha1];
        
        $res = self::find( $con );
        return $res;
    }
    
    /**
     * 上传信息保存
     * @param type $info    文件信息
     * @param type $path    保存路径
     * @param type $data    数据
     * @return type
     */
    public static function uplSave( $info ,$path='' ,$data = [])
    {
        $data['file_path']      = str_replace('\\', '/', $path);
        $data['md5']            = $info->md5();
        $data['sha1']           = $info->sha1();
        $data['status']         = '1';

        return self::save($data);
    }
            
}
