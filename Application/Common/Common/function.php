<?php
// 自定义code || msg || data 返回
function json_out($code, $msg, $data) {
    $result = array(
        "code" => $code,
        "msg" => $msg,
        "data" => $data
    );
    echo json_encode($result);
    exit;
}

// 返回错误结果
function resp_error($code, $msg) {
    $result = array(
        "code" => $code ? $code : -1,
        "msg" => $msg ? $msg : '出错了!'
    );
    echo json_encode($result);
    exit;
}

// 返回成功结果
function resp_success($data) {
    $result = array(
        "code" => 100,
        "msg" => "请求成功!",
        "data" => $data
    );
    echo json_encode($result);
    exit;
}