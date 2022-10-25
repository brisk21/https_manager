<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 生成由字母数字组成的随机数
 * @param int $len 生成几位数
 * @return string
 */
function getRandStr($len)
{
    $chars = array(
        "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z",
        "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

function data_return($msg = '操作成功', $data = [], $code = 0, $exit = true)
{
    $arr['code'] = $code;
    $arr['msg'] = $msg;
    $arr['data'] = $data;
    if (!$exit) return $arr;
    exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
}

function data_return_error($msg = '操作失败', $code = -1, $data = [], $exit = true)
{
    $arr['code'] = $code;
    $arr['msg'] = $msg;
    $arr['data'] = $data;
    if (!$exit) return $arr;
    exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
}