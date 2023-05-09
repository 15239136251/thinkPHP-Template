<?php
namespace Home\Controller;
use Home\Controller\CommonController;

class UserController extends CommonController {
    function _initialize() {
        parent::_initialize();
    }

    // 列表
    public function list() {
        $where['is_active'] ="Y";
        resp_success($this->user->getList($where));
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
        $params = $this->_getUserParams();
        if ($params["username"] && $params["password"]) {
            // 实例化 User 对象
            $where['is_active'] ="Y";
            $where['username'] = $params['username'];
            $data = $this->user->getList($where);
            if (count($data)) {
                resp_error(1, "当前用户名已注册，请重新填写用户名!");
            } else {
                $params["create_time"] = date("Y-m-d H:i:s");
                $params["modify_time"] = date("Y-m-d H:i:s");
                $result = $this->user->addData($params);
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
            $result = $this->user->updateData($id, $data);
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
            $this->user->delete($id);
            json_out(100, "删除成功!", null);
        } else {
            $result = resp_error(1, "ID未填写");
        }
    }

    private function _getUserParams() {
        $params['username'] = I("username");
		$params['password'] = I("password");
		return $params;
    }
}