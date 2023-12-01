<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Task;

use App\Cmd\CheckTimeCmd;
use App\Cmd\RealtimeDataCmd;
use App\Contract\AuthedChannel;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;

class ScheduleTask
{
    #[Inject]
    public AuthedChannel $authedChannel;

    #[Inject]
    protected StdoutLoggerInterface $logger;

    #[Crontab(rule: '1 *\/30 * * * *', memo: 'checkTime')]
    public function checkTime()
    {
        $channels = $this->authedChannel->getChannels();

        foreach ($channels as $pile => $fd) {
            $pile = strval($pile);
            $frame = CheckTimeCmd::make($pile);
            $this->authedChannel->send($pile, $frame);
        }
    }

    // 检测在线
//    #[Crontab(rule: '*\/15 * * * * *', memo: 'checkOnline')]
//    public function checkOnline()
//    {
//        $count = $this->authedChannel->count();
//        var_dump('在线终端数 ' . $count);
//    }

    #[Crontab(rule: '*\/1 * * * * *', memo: 'realtimeData')]
    public function realtimeData()
    {
        var_dump('在线终端数 1');
        $this->logger->info(date('Y-m-d H:i:s', time()));
//        $channels = $this->authedChannel->getChannels();
//
//        foreach ($channels as $pile => $fd) {
//            $pile = strval($pile);
//            $frame = RealtimeDataCmd::make($pile, '01');
//            $this->authedChannel->send($pile, $frame);
//        }
    }
}
