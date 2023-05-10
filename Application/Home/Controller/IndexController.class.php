<?php
namespace Home\Controller;
use Home\Controller\CommonController;

class IndexController extends CommonController {

	function _initialize() {
        parent::_initialize();
    }

    public function index() {
		
	}

	public function getCode() {
		$result = $this->checkToken();
		resp_success($result);
	}

	// 检测输入的验证码是否正确，$code为用户输入的验证码字符串
	function check_verify($code, $id = ''){
		$verify = new \Think\Verify();
		return $verify->check($code, $id);
	}

	public function login() {
		$params['username'] = I("username");
		$params['password'] = I("password");
		$params['code'] = I("code");

		if (!$params['username']) {
			resp_error(0, "未输入账号!");
		}

		if (!$params['password']) {
			resp_error(0, "未输入密码!");
		}

		if (!$params['code']) {
			resp_error(0, "未输入验证码!");
		}

		$where['is_active'] ="Y";
		$where['username'] = $params['username'];
		$data = $this->user->getList($where, array('page' => 1, 'pageSize' => 10), array('password', 'username'));
		
		if (count($data)) {
			// 有用户，判断密码是否一致
			if ($data[0]['password'] === $params['password']) {
				// $userinfo = $this->user->getUserInfo($data[0]['id']);

				$userinfo = [
					'id' => $data[0]['id'], 
					'name' => $data[0]['username']
				];

				$jwt = new \Common\Utils\Jwt();
				$jwt->setIss("{$data[0]['username']}");//JWT的签发者
				$jwt->setIat(time());//签发时间
				$jwt->setExp(time() + 60 * 60 * 24 * 1);//过期时间为1天
				$jwt->setClaim($userinfo);//存储数据
				//生成token并获取
				$token = $jwt->getToken();

				$result = array(
					'userinfo'=> $userinfo,
					'token'=> $token
				);
				resp_success($result, "登录成功!");
			} else {
				resp_error(0, "密码错误!");
			}
		} else {
			resp_error(0, '当前用户不存在，无法登录!');
		}
	}

	public function logout() {

	}

	private function _filterKey($val) {
		return $val == 'id';
	}
}

