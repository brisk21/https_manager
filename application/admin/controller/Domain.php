<?php


namespace app\admin\controller;


use app\api\controller\Task;
use app\common\service\Bs;
use app\common\service\Timer;

class Domain extends Base
{
    public function index()
    {
        $page = input('page', 1);
        $data = model('Domain')->list_data(input(), $page, 10);
        $this->assign('data', $data);
        return $this->fetch();
    }

    function form()
    {
        $id = input('id', 0);
        if ($id) {
            $this->assign('data', model('Domain')->fetch_data($id));
        }
        return $this->fetch();
    }

    public function action_data()
    {
        if (!request()->isPost()) {
            data_return('非法操作', [], -1);
        }
        $param = input('post.');
        if (!empty($param['type']) && $param['type'] == 'setStatus') {
            $res = model('Domain')->update_data($param);
        } else {
            if (!preg_match("/http[s]?:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is", $param['domain'])) {
                data_return('接口地址不合规', [], -1);
            }
            if (!empty($param['id'])) {
                $res = model('Domain')->update_data($param);
            } else {
                unset($param['id']);
                $res = model('Domain')->create_data($param);
            }
        }
        data_return('操作成功', $res);
    }

    //手动获取信息
    public function get_info()
    {
        if (!request()->isPost()) {
            data_return('非法操作', [], -1);
        }
        $id = request()->post('id');
        $uriInfo = model('Domain')->fetch_data($id);
        $info = Bs::get_cert_info($uriInfo['domain']);
        if ($info['code'] <> 0) {
            data_return_error($info['msg']);
        }
        Timer::check_domain(null, $id);
        data_return('获取完成');
    }
}