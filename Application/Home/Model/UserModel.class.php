<?php
namespace Home\Model;
use Think\Model;

use Home\Model\CommonModel;
class UserModel extends CommonModel{
    protected $tableName = 'user';

    public function getUserInfo($id) {
        return $id;
    }

   
}