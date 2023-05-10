<?php
namespace Home\Controller;
use Think\Controller\RestController;
use Home\Model\UserModel;
use Think\Request;

class CommonController extends RestController {
	protected $allowMethod    = array('get','post','put'); // REST允许的请求类型列表
    protected $allowType      = array('html','xml','json'); // REST允许请求的资源类型列表
    protected $user;
    protected $token;
    public function _initialize() {
        /*	*
          * 每次请求过来都会执行的方法
          */
        $request = __INFO__;

        $whitelist = array(
            'home/index/login',
            'home/index/logout',
            'home/index/getCode'
        );
        $noQuery = !strstr(__INFO__, 'list') && !strstr(__INFO__, 'detail');
        if (!in_array(__INFO__, $whitelist) && $noQuery) {
            $this->token = $this->checkToken();
        }
        $this->user = new UserModel();
        if ($this->token) {
            // 判断这个token内的用户是否存在，如果不存在就报错
            $data = $this->user->getDetail($this->token['id']);
            if (!$data) {
                resp_error(0, '当前用户无效！'.$this->token['id']);
            }
        }
    }
    
    /**
     * 初始化函数
     *
     * @return void
     */
    

    protected function getToken(){
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
        if (!$token) {
            resp_error(-100, '请先登录!');
        }
		$jwt = new \Common\Utils\Jwt();
		$verifyResult = $jwt->verifyToken($token);
		if (!$verifyResult) {
			resp_error(0, "登录失效，请重新登录!");
		} else {
            return $verifyResult['claim'];
		}
    }
}