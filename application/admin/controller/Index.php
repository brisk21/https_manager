<?php


namespace app\admin\controller;


class Index extends Base
{
    public function index()
    {
        $menus = config('menus.');
        $this->assign('menus', $menus);
        return $this->fetch();
    }

    public function welcome()
    {
        $data = [
            'domain' => model('Domain')->count(),
            'logEmail' => model('logEmail')->count(),
            'logEmailSuc' => model('logEmail')->where('status', '=', 1)->count(),
            'logEmailError' => model('logEmail')->where('status', '=', -1)->count('try_count'),
        ];
        $data['dbSize'] = round(filesize(str_replace('sqlite:', '', config('database.dsn'))) / 1024 / 1024, 2);
        $this->assign('data',$data);
        return $this->fetch();
    }


}