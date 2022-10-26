<?php

namespace app\command;


use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use Workerman\Worker;

/**
 *
 * @ClassName Timer
 * @Version 1.0
 * @Description
 * @package app\command
 */
class Timer extends Command
{
    // 定时器句柄/ID
    protected $timer;

    // 时间间隔 (单位: 秒, 默认5秒)
    protected $interval = 1;

    protected function configure()
    {
        $this->setName('timer')
            ->addArgument('status', Argument::REQUIRED, 'start/stop/reload/status/connections')
            ->addOption('d', null, Option::VALUE_NONE, 'daemon（守护进程）方式启动')
            ->addOption('i', null, Option::VALUE_OPTIONAL, '多长时间执行一次')
            ->setDescription('start/stop/reload/status/connections 定时任务。eg: php think timer start --d --i 3 表示守护进程方式启动，每隔3秒执行定时器');
    }

    /**
     * 创建定时器
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     */
    protected function execute(Input $input, Output $output)
    {
        $this->init($input, $output);
        // 创建定时器任务
        $worker = new Worker();
        $worker->count = 2;
        $worker->onWorkerStart = [$this, 'start'];
        $worker::runAll();
    }

    /**
     * 定时器执行的内容
     * @return false|int
     */
    public function start(Worker $worker)
    {
        // 第一个进程执行检查
        if ($worker->id === 0) {
            $this->timer = \Workerman\Lib\Timer::add($this->interval, function () {
                try {
                    trace('任务开始' . date('y-m-d H:i:s'), 'check_domain');

                    \app\common\service\Timer::check_domain();

                    trace('任务结束' . date('y-m-d H:i:s'), 'check_domain');

                } catch (\Exception $e) {
                    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
                    $this->stop();
                }
            });
        }

        // 第二个进程执行发送邮件
        if ($worker->id === 1) {
            $this->timer = \Workerman\Lib\Timer::add($this->interval, function () {
                try {
                    \app\common\service\Timer::send_mail();

                } catch (\Exception $e) {
                    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
                    $this->stop();
                }
            });
        }

        return $this->timer;
    }

    /**
     * 停止/删除定时器
     * @return bool
     */
    public function stop()
    {
        return \Workerman\Lib\Timer::del($this->timer);
    }

    protected function init(Input $input, Output $output)
    {
        global $argv;

        if ($input->hasOption('i')) {
            $this->interval = (float)$input->getOption('i');
        }

        $argv[1] = $input->getArgument('status') ?: 'start';
        if ($input->hasOption('d')) {
            $argv[2] = '-d';
        } else {
            unset($argv[2]);
        }
    }
}
