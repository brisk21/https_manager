<?php


namespace app\admin\controller;


use think\Controller;

class User extends Controller
{
    /**
     * 密码错误次数限制
     * @var int
     */
    protected $errorCount = 5;

    /**
     * 错误次数缓存时间
     * @var int
     */
    protected $errorCountKeyExpire = 300;

    public function login()
    {
        if (!request()->isPost()) {
            return $this->fetch();
        }
        $param = request()->post();
        if (empty($param['username'])) data_return('请填写登录账号', [], -1);
        if (empty($param['password'])) data_return('请填写登录密码', [], -1);
        if (empty($param['verifyCode'])) data_return('请填写验证码', [], -1);
        if (!captcha_check($param['verifyCode'])) data_return('验证码不匹配', [], -1);
        //获取管理员，后期扩展可以从数据库
        $admins = array_column(config('admin.admin'), null, 'username');
        if (!key_exists($param['username'], $admins)) {
            data_return('账号不存在', [], -1);
        } elseif ($admins[$param['username']]['status'] != 1) {
            data_return('账号已被限制登录', [], -1);
        }

        $errorCountKey = 'error_count' . $param['username'];
        $errorCount = intval(cache($errorCountKey));
        if ($errorCount >= $this->errorCount) data_return('密码错误次数过多，请稍后重试', [], -1);

        if ($param['password'] !== $admins[$param['username']]['password']) {
            $errorCount++;
            cache($errorCountKey, $errorCount, $this->errorCountKeyExpire);
            data_return('账号密码不匹配', [cache($errorCountKey)], -1);
        }
        cache($errorCountKey, null);
        unset($admins[$param['username']]['password']);
        //缓存
        session('admin',$admins[$param['username']]);
        trace(['ctime' => date('Y-m-d H:i:s'), 'ip' => request()->ip(), 'username' => $param['username']], 'admin_login');
        data_return('登录成功', $admins);
    }

    public function logout()
    {
        session('admin', null);
        return $this->redirect(url('/admin/user/login'));
    }

}