<?php


namespace app\admin\controller;


use app\common\model\Conf;

class Sys extends Base
{
    public function user()
    {
        $key = 'manager_user';
        $data = model('Conf')->fetch_data($key);
        if (request()->isPost()) {
            $param = request()->post();
            $Arr = array_unique(array_filter(explode(',', str_replace(['，', '|'], ',', $param['users']))));
            if (empty($Arr)) data_return('请填写邮箱', '', -1);
            $mobiles = join(',', $Arr);
            if ($data) {
                model('Conf')->update_data($key, $mobiles);
            } else {
                model('Conf')->create_data([
                    'key' => $key,
                    'value' => $mobiles
                ]);
            }
            data_return('更新成功');
        }
        $this->assign('data', $data);
        return $this->fetch();
    }

    public function email()
    {
        $key = 'email';
        $data = model('Conf')->fetch_data($key);
        if (request()->isPost()) {
            //https证书到期提示
            $param = request()->only('host,username,password,port,website,title,encryption');
            $param = json_encode($param,JSON_UNESCAPED_UNICODE);
            if ($data) {
                model('Conf')->update_data($key, $param);
            } else {
                model('Conf')->create_data([
                    'key' => $key,
                    'value' => $param
                ]);
            }
            data_return('更新成功');
        }
        $this->assign('data', $data?json_decode($data['value'],true):[]);
        return $this->fetch();
    }

}