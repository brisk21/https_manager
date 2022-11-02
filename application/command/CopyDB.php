<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;


class CopyDB extends Command
{
    protected function configure()
    {
        $this->setName('copy_db_file')
            ->setDescription("复制sqlite数据库")
            ->setHelp("php think copy_db_file");
    }


    protected function execute(Input $input, Output $output)
    {
        if (config('database.type') <> 'sqlite') {
            $output->writeln('当前类型' . config('database.type') . '，非sqlite，不用复制sqlite数据库，后期修改可以手动执行 ');
            return;
        }

        $rootDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

        $filePath = $rootDir . 'install' . DIRECTORY_SEPARATOR . 'struct.db';

        $targetFile = $rootDir . 'data' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'manager.db';

        if (!is_dir($rootDir . 'data' . DIRECTORY_SEPARATOR . 'db')) {
            mkdir($rootDir . 'data' . DIRECTORY_SEPARATOR . 'db', '0777', true);
        }

        if (!file_exists($filePath)) {
            $output->writeln('源数据库文件不存在 ' . date('Y-m-d H:i:s'));
        } else {
            if (file_exists($targetFile)) {
                $output->writeln('数据库文件已复制, 忽略本次操作 ' . date('Y-m-d H:i:s'));
            } else {
                if (!copy($filePath, $targetFile)) {
                    $output->writeln('复制数据库文件失败! ' . date('Y-m-d H:i:s'));
                } else {
                    $output->writeln('复制数据库文件结束... ' . date('Y-m-d H:i:s'));
                }
            }
        }
    }
}
