<?php
namespace Home\Controller;
use Think\Controller\RestController;
class IndexController extends RestController {
	
	protected $allowMethod    = array('get','post','put'); // REST允许的请求类型列表
    protected $allowType      = array('html','xml','json'); // REST允许请求的资源类型列表

    Public function index(){
        // 输出id为1的Info的html页面
		$input=file_get_contents('php://input');
		//dump(json_decode($input,true));
		$arr =  D('myguests')->select();
		$this->response(json_out(100, "请求成功!", $arr));
    }

	public function hello() {
		$this->response('');
	}

	public function getParams() {

		$params['value'] = I("value");
		$params['name'] = I("name");
		$this->response(resp_success($params));
		return $params;
	}
   
}

