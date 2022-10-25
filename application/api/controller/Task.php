<?php

namespace app\api\controller;

use app\common\model\LogEmail;
use app\common\service\Bs;
use app\common\service\Timer;
use think\Controller;

class Task extends Controller
{
    //定时发送提醒
    public function send_email()
    {
        $key = md5(__FUNCTION__ . __FILE__ . 'ABCd');
        if (cache($key)) data_return_error('有任务在执行');
        cache($key, 1, 600);
        Timer::send_mail();
        cache($key, null);
        data_return('发送完成');
    }

    //定时检测域名证书有效期
    public function check_domain()
    {
        $key = md5(__FUNCTION__ . __FILE__ . 'ABC');
        if (cache($key)) data_return_error('有任务在执行');
        cache($key, 1, 120);
        Timer::check_domain();
        cache($key, null);
        data_return('检测完成');
    }

}