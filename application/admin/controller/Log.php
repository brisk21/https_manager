<?php


namespace app\admin\controller;


class Log extends Base
{
    //通知记录
    public function email()
    {
        $page = input('page', 1);
        $data = model('LogEmail')->list_data(input(), $page, 10);
        $this->assign('data', $data);
        return $this->fetch();
    }
}