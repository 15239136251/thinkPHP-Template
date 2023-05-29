<?php
namespace Home\Model;
use Think\Model;

class CommonModel extends Model{
    // 列表（分页）
    function list($params) {
        // 预处理
        {
            // 查询条件
            $where = $params['where'] ? $params['where'] : array();
            // 分页
            $page = $params['page'] ? $params['page'] : 1;
            // 分页数量
            $pageSize = $params['pageSize'] ? $params['pageSize'] : 10;
            // 一些默认要返回的字段，例如：id，修改时间，修改人，创建时间，创建人
            $_field = $this->getDefaultField();
            // 需要返回的其他字段
            $field = $params['field'] ? $params['field'] : array('*');
            // order 排序
            $order = $params['order'] ? $params['order'] : array();
            // group 分组
            $group = $params['group'] ? $params['group'] : '';
        }
        // 查询数据库
        $data = $this->field(array_merge($field, $_field))
                    ->where($where)
                    ->limit($pageSize)
                    ->page($page)
                    ->order($order)
                    ->group($group)
                    ->select();
        return $data;
    }

    // 列表（不分页）
    function noPage($params) {
        // 预处理
        {
            // 查询条件
            $where = $params['where'] ? $params['where'] : array();
            // 一些默认要返回的字段，例如：id，修改时间，修改人，创建时间，创建人
            $_field = $this->getDefaultField();
            // 需要返回的其他字段
            $field = $params['field'] ? $params['field'] : array('*');
            // order 排序
            $order = $params['order'] ? $params['order'] : array();
            // group 分组
            $group = $params['group'] ? $params['group'] : '';
        }
        // 查询数据库
        $data = $this->field(array_merge($field, $_field))
                    ->where($where)
                    ->order($order)
                    ->group($group)
                    ->select();
        return $data;
    }

    // 详情
    function detail($params) {
        $where = array('id' => $params['id']);
        $_field = $this->getDefaultField();
        // 需要返回的其他字段
        $field = $params['field'] ? $params['field'] : array('*');
        $data = $this->field(array_merge($field, $_field))->where($where)->find();
        return $data;
    }

     // 更新或保存数据
     function saveData($type = 'add') {
        $token = $this->checkToken();
        $data = $this->create();
        $data["modify_time"] = date("Y-m-d H:i:s");
        $data["modify_id"] = $token['id'];
        if(!$data){
            return false;
        }
        if($type == 'add'){
            $data["create_time"] = date("Y-m-d H:i:s");
            $data["create_id"] = $token['id'];
            $result = $this->add();
            return $result;
        }
        if($type == 'edit'){
            if(empty($data['id'])){
                return false;
            }
            $status = $this->save();
            if($status === false){
                return false;
            }
            return true;
        }
        return false;
    }

    function addData($params) {
        $token = $this->checkToken();
        $_data["create_time"] = date("Y-m-d H:i:s");
        $_data["create_id"] = $token['id'];
        $_data["modify_time"] = date("Y-m-d H:i:s");
        $_data["modify_id"] = $token['id'];
        $this->create(array_merge($params, $_data));
        $lastId = $this->add();
        $result = $this->detail($lastId);
        return $result;
    }

    function updateData($id, $data) {
        $token = $this->checkToken();
        $_data["modify_time"] = date("Y-m-d H:i:s");
        $_data["modify_id"] = $token['id'];
        $this->where("id = {$id}")->save(array_merge($data, $_data));
        $result = $this->detail($id);
        return $result;
    }

    // 删除数据（软删除）
    function deletes($ids) {
        try {
            $token = $this->checkToken();
            $where['id'] = array('exp',' IN ('.$ids.') ');
            $where['is_active'] = 'Y';
            $_data["modify_time"] = date("Y-m-d H:i:s");
            $_data["modify_id"] = $token['id'];
            $_data["is_active"] = 'N';
            $this->where($where)->save($_data);
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    // 删除数据（硬删除）

    private function getDefaultField() {
        return  array(
            'id', 
            'create_time', 
            '(select username from admin a where a.id = create_id)' => 'create_name', 
            'modify_time',
            '(select username from admin a where a.id = modify_id)' => 'modify_name',
        );
    }

    private function getToken(){
        if(function_exists('apache_request_headers')){
            $header = apache_request_headers();
            $token = $header['Authorization'];
        }else{
            $token = $_SERVER['HTTP_AUTHORIZATION'];
        }
        return $token;
	}

    protected function checkToken() {
        $token = $this->getToken();
		$jwt = new \Common\Utils\Jwt();
		$verifyResult = $jwt->verifyToken($token);
		if (!$verifyResult) {
			resp_error(-101, "登录失效，请重新登录!");
		} else {
            return $verifyResult['claim'];
		}
    }
}