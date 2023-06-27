<?php
namespace Home\Model;
use Think\Model;

use Home\Model\CommonModel;
class MenuModel extends CommonModel{
    protected $tableName = 'menu_rule';

    public function getMenu($id) {
        return $id;
    }
}