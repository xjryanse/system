<?php
namespace xjryanse\system\model;

/**
 * 上传附件
 */
class SystemFile extends Base
{
    /**
     * 文件路径
     * @param type $value
     * @return type
     */
    public function getFilePathAttr( $value )
    {
        return $value ? config('xiesemi.systemBaseUrl') .'/'. $value : $value;
    }

}