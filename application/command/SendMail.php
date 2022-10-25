<?php

namespace app\command;

use app\common\model\Conf;
use app\common\model\LogEmail;
use app\common\service\Bs;
use app\common\service\Timer;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

use think\facade\Env;
use function Composer\Autoload\includeFile;

class SendMail extends Command
{
    protected function configure()
    {
        $this
            ->setName('send_email')
            ->setDescription("计划任务，发送邮件")
            ->setHelp("php think send_email");
    }


    protected function execute(Input $input, Output $output)
    {
        $output->writeln('开始进入任务...'.date('y-m-d H:i:s'));

        trace('任务开始' . date('y-m-d H:i:s'), 'send_email');

        Timer::send_mail($output);

        $output->writeln('任务执行结束...'.date('y-m-d H:i:s'));

        trace('任务结束' . date('y-m-d H:i:s'), 'send_email');
    }
}