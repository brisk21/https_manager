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

class Domain extends Command
{
    protected function configure()
    {
        $this
            ->setName('check_domain')
            ->setDescription("计划任务，定时检查域名证书状态")
            ->setHelp("php think check_domain");
    }


    protected function execute(Input $input, Output $output)
    {
        $output->writeln('开始进入检测任务...'.date('y-m-d H:i:s'));

        trace('任务开始' . date('y-m-d H:i:s'), 'check_domain');

        Timer::check_domain($output);

        $output->writeln('任务检测执行结束...'.date('y-m-d H:i:s'));

        trace('任务结束' . date('y-m-d H:i:s'), 'check_domain');
    }
}