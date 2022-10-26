<?php


namespace app\common\service;


use app\common\model\Conf;
use app\common\model\Domain;
use app\common\model\LogEmail;

class Timer
{
    public static function check_domain($output = null)
    {
        $model = new Domain();
        $where[] = ['status', '=', 1];
        //每天一次
        $where[] = ['last_check_time', '<', strtotime('today')];
        $data = $model->list_data_for_check($where, input('limit', 30, 'intval'));
        if (empty($data)) {
            return data_return_error('暂无需要检测的域名', -1, [], false);
        }
        $update = [];
        $notices = [];
        //提前x天提示
        $day = config('notice.https_expire');
        // 是否发送测试邮件
        $sendMailImmediately = config('notice.send_mail_immediately');
        foreach ($data as $value) {
            $info = Bs::get_cert_info($value['domain']);
            $up = [
                'last_check_time' => time(),
                'id' => $value['id'],
            ];
            if (!empty($info['data']['validFrom_time_t'])) {
                $update[] = array_merge($up, [
                    'start_time' => $info['data']['validFrom_time_t'],
                    'end_time' => $info['data']['validTo_time_t'],
                ]);
                $noticeExpire = ($day > 0 ? $day : 15) * 86400;
                if ($sendMailImmediately || $info['data']['validTo_time_t'] < time() + $noticeExpire) {
                    $notices[] = [
                        'content' => '域名' . (Bs::parse_domain($value['domain'])) . '的https证书即将到期，到期时间为：' . date('Y-m-d H:i:s', $info['data']['validTo_time_t']) . '，请及时续约，以免到期造成访问异常问题~'
                    ];
                }
            }
        }
        $model->saveAll($update);
        if (!empty($notices)) {
            //多人接收、多记录
            $accounts = (new Conf())->getValue('manager_user');
            if (!empty($accounts)) {
                $queue = [];
                $accounts = array_filter(array_unique(explode(',', $accounts)));
                foreach ($notices as $item) {
                    foreach ($accounts as $account) {
                        $queue[] = [
                            'email' => $account,
                            'content' => $item['content']
                        ];
                    }
                }
            } elseif (is_object($output)) {
                $output->writeln('未配置接收邮箱！！！！！！');
            }
            if (!empty($queue)) {
                is_object($output) && $output->writeln('添加消息推送队列:' . count($queue));
                (new LogEmail())->create_all($queue);
            }
        }
        return data_return('success', [], 0, false);
    }

    public static function send_mail($limit = 10, $output = null)
    {
        set_time_limit(0);
        $model = new LogEmail();
        $where[] = ['status', 'in', [0, -1]];
        $where[] = ['try_count', '<', 3];
        $data = $model->list_data_for_send($where, $limit);
        if (empty($data)) {
            is_object($output) && $output->writeln('暂无需要发送队列');
            return data_return_error('暂无需要发送队列', -1, [], false);
        }
        $update_data = [];
        foreach ($data as $item) {
            $status = -1;
            $res = Bs::send_email($item);
            if ($res['code'] == 0) {
                $status = 1;
            }
            $item['try_count'] += 1;
            $update_data[] = [
                'id' => $item['id'],
                'up_time' => time(),
                'try_count' => $item['try_count'],
                'status' => $status
            ];
        }
        is_object($output) && $output->writeln('发送操作完成：' . count($update_data));
        $model->saveAll($update_data);

        return data_return('ok', count($data), 0, false);
    }
}
