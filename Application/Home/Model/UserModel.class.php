<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model{
    protected $tableName = 'user';

    function getList($where = "") {
        $data = $this->where("is_active = 'Y'".$where)->select();
        return $data;
    }

    function getDetail($id) {
        $data = $this->where("id = {$id} AND is_active = 'Y'")->find();
        return $data;
    }

    function add($params) {
        $this->create($params);
        $lastId = $this->add();
        return $lastId;
    }
}