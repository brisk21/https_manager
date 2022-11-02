<?php


namespace app\admin\controller;


use think\Db;

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

        if (config('database.type') == 'sqlite') {
            $data['dbSize'] = round(filesize(str_replace('sqlite:', '', config('database.dsn'))) / 1024 / 1024, 2);
        } else {
            $query = Db::query(" select round(sum(DATA_LENGTH/1024/1024),2) as data from information_schema.TABLES where TABLE_SCHEMA ='" . (config('database.database')) . "' LIMIT 1;");
            $data['dbSize'] = !empty($query[0]['data']) ? $query[0]['data'] : '0.00';
        }

        $this->assign('data', $data);
        return $this->fetch();
    }


}