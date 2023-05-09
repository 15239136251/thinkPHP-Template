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
function resp_error($code = -1, $msg = "请求失败!") {
    $result = array(
        "code" => $code,
        "msg" => $msg
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