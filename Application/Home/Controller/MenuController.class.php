<?php
namespace Home\Controller;
use Home\Controller\CommonController;
class MenuController extends CommonController {
	protected $allowMethod    = array('get','post','put'); // REST允许的请求类型列表
    protected $allowType      = array('html','xml','json'); // REST允许请求的资源类型列表

    public function index(){
    
	}
}