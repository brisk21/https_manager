<?php
return [
    //https证书到期前x天提醒
    'https_expire' => 15,
    // 是否执行实时通知，开启后，每次检查域名都将发送邮件，不再到期前再提醒
    'send_mail_immediately' => 0
];
