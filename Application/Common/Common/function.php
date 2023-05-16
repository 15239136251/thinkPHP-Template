<?php
// 自定义code || msg || data 返回
function json_out($code = -1, $msg = "请求失败!", $data) {
    $result = array(
        "code" => $code,
        "msg" => $msg,
        "data" => $data
    );
    echo json_encode($result);
    exit;
}

// 返回错误结果
function resp_error($code = -1, $msg = '') {
    $error = require "./Application/Common/Common/error.php";
    $result = array(
        "code" => $code,
        "msg" => $msg ? $msg : $error[$code],
    );
    echo json_encode($result);
    exit;
}

// 返回成功结果
function resp_success($data, $msg = "请求成功!") {
    $result = array(
        "code" => 100,
        "msg" => $msg ,
        "data" => $data
    );
    echo json_encode($result);
    exit;
}

// 列表返回成功结果
function resps_success($data, $page, $pageSize, $msg = "查询成功!") {
    $result = array(
        "code" => 100,
        "msg" => $msg ,
        "page" => $page,
        "pageSize" => $pageSize,
        "data" => $data
    );
    echo json_encode($result);
    exit;
}

// 获取查询条件，如果没有则不返回
function getParams($array = array()) {
    $params = array();
    foreach ($array as $type => $type_value) {
        switch ($type) {
            case 'like':
                foreach ($type_value as $key => $value) {
                    if (I($value)) $params[$value] = array('like', '%'.I($value).'%');
                }
                break;
            case 'egt':
                foreach ($type_value as $key => $value) {
                    if (I($value)) $params[$value] = array('EGT', I($value));
                }
                break;
            case 'elt':
                foreach ($type_value as $key => $value) {
                    if (I($value)) $params[$value] = array('ELT', I($value));
                }
                break;
            case 'in':
                foreach ($type_value as $key => $value) {
                    if (I($value)) $params[$value] = array('exp',' IN ('.$value.') ');
                }
                break;
            default:
                foreach ($type_value as $key => $value) {
                    if (I($value)) $params[$value] = I($value);
                        else if ($value === 'is_active' || $value === 'isactive') $params['is_active'] = 'Y';
                }
                break;
        }
    }
    return $params;
}


function getTree($array, $pid = 0, $level = 0) {
    $data = array();
    foreach ($array as $key => $value) {
        # code...
        if ($value['pid'] == $pid) {
            $_value = $value;
            $_value['children'] = getTree($array,$value['id']);
            array_push($data, $_value);
        }
    }
    return $data;
}