<?php

namespace xjryanse\system\service\authJobRole;

use xjryanse\logic\DbOperate;
/**
 * 
 */
trait ListTraits{
    
    public static function listTest($param = []){

        $fields[] = 'tA.id'; 
        $fields[] = 'tA.dept_name';

        dump(DbOperate::generateJoinSql($fields));

        return [];

    }

}
