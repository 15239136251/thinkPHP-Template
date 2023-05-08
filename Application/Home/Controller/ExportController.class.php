<?php
namespace Home\Controller;
use Think\Controller\RestController;


class ExportController extends RestController {
    protected $allowMethod = array('get','post','put'); // REST允许的请求类型列表
    protected $allowType = array('json'); // REST允许请求的资源类型列表

    private $backup_file = "backup.sql"; // 备份文件
    private $log_file = "backup.log"; // 备份日志

    // 导出
    function export_backup() {
        $params = $this->getUserParams();
        $Table = M()->query("show tables like '".$params['tbname']."'");
        if($Table){
            $config = require "./Application/Common/Conf/config.php";
            exec("mysqldump -h {$config['DB_HOST']} -u {$config['DB_USER']} -p{$config['DB_PWD']} {$config['DB_Name']} > ./BackupSql/{$params['tbname']}.sql");
            $str = "mysqldump -h {$config['DB_HOST']} -u {$config['DB_USER']} -p{$config['DB_PWD']} {$config['DB_Name']} > ./BackupSql/{$params['tbname']}.sql";
            resp_success($str);
        }else{
            resp_error(999, "数据库中{$params['tbname']}表不存在！无法导出");
        }
    }

    private function getUserParams() {
        $params['tbname'] = I("tbname");
		return $params;
    }
}