<?php
namespace Home\Model;
use Think\Model;

class CommonModel extends Model{
    function getList($where, $page = array('page' => 1, 'pageSize' => 10), $field = array()) {
        $_field = array(
            'id', 
            'create_time', 
            '(select username from admin a where a.id = create_id)' => 'create_name', 
            'modify_time',
            '(select username from admin a where a.id = modify_id)' => 'modify_name',
        );
        $data = $this->field(array_merge($field, $_field))
                        ->where($where)
                        ->limit($page['pageSize'])
                        ->page($page['page'])
                        ->select();
        return $data;
    }

    function getDetail($id) {
        $data = $this->where("id = {$id} AND is_active = 'Y'")->find();
        return $data;
    }

    function addData($params) {
        $token = $this->checkToken();
        $_data["create_time"] = date("Y-m-d H:i:s");
        $_data["create_id"] = $token['id'];
        $_data["modify_time"] = date("Y-m-d H:i:s");
        $_data["modify_id"] = $token['id'];
        $this->create(array_merge($params, $_data));
        $lastId = $this->add();
        $result = $this->getDetail($lastId);
        return $result;
    }

    function updateData($id, $data) {
        $token = $this->checkToken();
        $_data["modify_time"] = date("Y-m-d H:i:s");
        $_data["modify_id"] = $token['id'];
        $this->where("id = {$id}")->save(array_merge($data, $_data));
        $result = $this->getDetail($id);
        return $result;
    }

    function deletes($ids) {
        try {
            $where['id'] = array('exp',' IN ('.$ids.') ');
            $where['is_active'] = 'Y';
            $_data["modify_time"] = date("Y-m-d H:i:s");
            $_data["modify_id"] = $token['id'];
            $_data["is_active"] = 'N';
            $this->where($where)->save($_data);
            //code...
            return 1;
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
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
			resp_error(0, "登录失效，请重新登录!");
		} else {
            return $verifyResult['claim'];
		}
    }
}