<?php


namespace app\common\service;


use app\common\model\Conf;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Bs
{
    /**
     * 获取https证书信息
     * @param $url
     * @return array
     */
    public static function get_cert_info($url)
    {
        if (!extension_loaded('openssl') || !is_callable('openssl_x509_parse')) {
            return ['code' => -1, 'msg' => '请开启openssl扩展'];
        }
        //不规范入参处理
        $domain = self::parse_domain($url);
        $context = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true,
                'capture_peer_cert_chain' => true,
            ],
        ]);
        $client = @stream_socket_client("ssl://" . $domain . ":443", $errno, $errstr, 10, STREAM_CLIENT_CONNECT, $context);
        if ($client == false) {
            return ['code' => -1, 'msg' => $domain . '未查到可靠信息', 'err' => [
                'errno' => $errno,
                'errstr' => iconv('gbk', 'utf-8', $errstr),
            ]];
        }
        $params = stream_context_get_params($client);
        if (empty($params['options']['ssl']['peer_certificate'])) {
            return ['code' => -1, 'msg' => $domain . '获取信息失败，请确保可以正常访问'];
        }
        $cert = $params['options']['ssl']['peer_certificate'];
        $cert_info = @openssl_x509_parse($cert);
        return ['code' => 0, 'data' => $cert_info];
    }

    public static function parse_domain($url)
    {
        //不规范入参处理
        $parse = parse_url($url);
        if (!empty($parse['host'])) {
            $domain = $parse['host'];
        } elseif (empty($parse['path'])) {
            return '';
        } else {
            //abc.top/x/xx
            $arr = explode('/', $parse['path']);
            $domain = $arr[0];
        }
        return $domain;
    }

    public static function send_email($data)
    {
        $mail = new PHPMailer(true);
        $config = (new Conf())->getValue('email');
        if (empty($config)) {
            trace('未配置发件系统', 'error');
            data_return_error('未配置发件系统');
        }
        $config = json_decode($config, true);
        try {
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = $config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['username'];
            $mail->Password = $config['password'];
            $mail->SMTPSecure = !empty($config['encryption']) ? strtolower($config['encryption']) : PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = $config['port'];

            $mail->CharSet = PHPMailer::CHARSET_UTF8;

            //Recipients
            $mail->setFrom($mail->Username, $config['website']);
            $mail->addAddress($data['email'], '管理员');
            //Content
            $mail->isHTML(true);
            $mail->Subject = $config['title'];
            $mail->Body = $data['content'];
            $mail->AltBody = strip_tags($data['content']);
            $mail->send();
            return ['code' => 0, 'msg' => '发送成功'];
        } catch (Exception $e) {
            return ['code' => -1, 'msg' => '发送异常' . $mail->ErrorInfo];
        }
    }


}