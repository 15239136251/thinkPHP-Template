<?php
namespace Home\Controller;
use Think\Controller\RestController;

use Home\Model\UserModel;

class UserController extends RestController {
	protected $allowMethod = array('get','post','put'); // REST允许的请求类型列表
    protected $allowType = array('json'); // REST允许请求的资源类型列表
    private $user;
    public function __construct(){ 
        $this->user = new UserModel();
    } 
    
    // 列表
    public function list() {
        resp_success($this->user->getList());
    }

    // 查询单个
    public function detail() {
        $id = I("id");
        if ($id) {
            resp_success($this->user->getDetail($id));
        } else {
            resp_error(0, "请传递需要查询的id");
        }
    }

    // 新增 
    public function add() {
        $params = $this->getUserParams();
        if ($params["username"] && $params["password"]) {
            // 实例化 User 对象
            $User = M("User");
            $where['is_active'] ="Y";
            $where['username'] = $params['username'];
            $data = $User->where($where)->find();
            if ($data) {
                resp_error(1, "当前用户名已注册，请重新填写用户名!");
            } else {
                $params["create_time"] = date("Y-m-d H:i:s");
                $params["modify_time"] = date("Y-m-d H:i:s");
                $lastId = $this->user->add($params);
                $result = $this->user->getDetail($id);
                json_out(100, "新增成功!", $result);
            }
        } else {
            $msg = $params->username ? "密码未填写！" : "用户名未填写！";
            $result = resp_error(1, $msg);
        }
    }

    // 更新
    public function update() {
        $id = I("id");
        $data["password"] = I("password");
        if ($id && $data["password"]) {
            $User = M("User");
            $User->where("id = {$id}")->save($data);
    
            $result = $this->detail($id);
            json_out(100, "更新成功!", $result);
        } else {
            $msg = $id ? "密码未填写！" : "ID未填写！";
            $result = resp_error(1, $msg);
        }
    }

    // 删除
    public function delete() {
        $id = I("id");
        if ($id) {
            $User = M("User");
            $User->where("is_active = 'Y'")->delete($id);
            json_out(100, "删除成功!", null);
        } else {
            $result = resp_error(1, "ID未填写");
        }
    }

    private function getUserParams() {
        $params['username'] = I("username");
		$params['password'] = I("password");
		return $params;
    }
}