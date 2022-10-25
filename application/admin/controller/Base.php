<?php


namespace app\admin\controller;


use think\App;
use think\Controller;

class Base extends Controller
{
    protected $admin = null;

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->admin = session('admin');
        if (empty($this->admin)) {
            if (request()->isAjax()){
                data_return('登录超时，请重新登录','',401);
            }
            return $this->error('登录超时，请重新登录', url('/admin/user/login'),'',1);
        }
        $this->assign('admin',$this->admin);
    }
}