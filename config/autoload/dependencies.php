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
use App\Contract\AuthedChannel as Channel;
use App\Server\AuthedChannelMemory as Memory;
use App\Server\AuthedChannelRedis as Redis;

$channel = require __DIR__ . '/channel.php';
return [
    Channel::class => $channel['type'] == 'memory' ? Memory::class : Redis::class,
];
