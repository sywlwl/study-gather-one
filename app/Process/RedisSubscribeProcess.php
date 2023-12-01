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
namespace App\Process;

use App\Cmd\CheckTimeCmd;
use App\Contract\AuthedChannel;
use Hyperf\Process\AbstractProcess;

// #[Process(name: 'redis-subscribe', pipeType: 1, enableCoroutine: true)]
class RedisSubscribeProcess extends AbstractProcess
{
    public function handle(): void
    {
        $redis = $this->container->get(\Redis::class);
        $authedChannel = $this->container->get(AuthedChannel::class);
        var_dump($authedChannel->getChannels());
        $redis->subscribe(['gather'], function ($redis, $chan, $msg) use ($authedChannel) {
            $channels = $authedChannel->getChannels();
            var_dump($channels);
            foreach ($channels as $pile => $fd) {
                $pile = strval($pile);
                $frame = CheckTimeCmd::make($pile, '');
                $authedChannel->send($pile, $frame);
            }
        });
    }
}
