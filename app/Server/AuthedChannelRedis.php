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
namespace App\Server;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;

class AuthedChannelRedis extends \App\Contract\AuthedChannel
{
    #[Inject]
    protected Redis $redis;

    public function addChannel(string $pile, $fd): void
    {
        $value = [
            'pile' => $pile,
            'fd' => strval($fd),
            'timestamp' => strval(time()),
        ];
        $this->redis->hSet($this->key, $pile, json_encode($value));
    }

    public function getChannel(string $pile): ?int
    {
        $value = $this->redis->hGet($this->key, $pile);
        if ($value === false) {
            return null;
        }
        $ret = json_decode($value, true);
        return intval($ret['fd']);
    }

    public function getChannels(): array
    {
        $ret = $this->redis->hGetAll($this->key);
        $map = [];
        if ($ret) {
            foreach ($ret as $pile => $value) {
                $value = json_decode($value, true);
                $map[$pile] = $value;
            }
        }
        return $map;
    }

    public function removeChannel(string $pile): void
    {
        $this->redis->hDel($this->key, $pile);
    }

    public function removeChannelByFd(int $fd): void
    {
        $ret = $this->redis->hGetAll($this->key);
        if ($ret) {
            foreach ($ret as $pile => $value) {
                $value = json_decode($value, true);
                if ($fd == intval($value['fd'])) {
                    $this->removeChannel($pile);
                    break;
                }
            }
        }
    }

    public function hasChannel(string $pile): bool
    {
        return $this->redis->hExists($this->key, $pile);
    }
}
