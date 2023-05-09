<?php
namespace Home\Model;
use Think\Model;

class CommonModel extends Model{
    function getList($where, $page) {
        $row = ($page['page'] - 1) * $page['pageSize'];
        $data = $this->where($where)->limit($row.','.$page['pageSize'])->select();
        return $data;
    }

    function getDetail($id) {
        $data = $this->where("id = {$id} AND is_active = 'Y'")->find();
        return $data;
    }

    function addData($params) {
        $this->create($params);
        $lastId = $this->add();
        $result = $this->getDetail($lastId);
        return $result;
    }

    function updateData($id, $data) {
        $this->where("id = {$id}")->save($data);
        $result = $this->getDetail($id);
        return $result;
    }

    function deletes($ids) {
        $this->delete($ids);
        return 1;
    }
}