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
namespace App\Contract;

use App\Codec\Encoder;
use App\Codec\Frame;
use App\Server\Close;
use Hyperf\Contract\ConfigInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

abstract class AuthedChannel
{
    protected string $key = '';

    protected array $config = [];

    public function __construct(ConfigInterface $config, protected EventDispatcherInterface $eventDispatcher)
    {
        $this->config = $config->get('channel');
        $key = $this->config['type'];
        $this->key = $this->config[$key]['identifier'];
    }

    abstract public function addChannel(string $pile, $fd): void;

    abstract public function getChannel(string $pile): ?int;

    abstract public function getChannels(): array;

    abstract public function removeChannel(string $pile): void;

    abstract public function removeChannelByFd(int $fd): void;

    abstract public function hasChannel(string $pile): bool;

    public function send(string $pile, Frame $frame): void
    {
        if ($this->hasChannel($pile)) {
            $fd = $this->getChannel($pile);
            $this->eventDispatcher->dispatch(new Encoder($frame, $fd));
        }
    }

    /**
     * 当前在线终端.
     */
    public function count(): int
    {
        $count = 0;
        $channels = $this->getChannels();
        foreach ($channels as $pile => $value) {
            if (time() - intval($value['timestamp']) > $this->config['timeout']) {
                $this->removeChannel(strval($pile));
                $this->eventDispatcher->dispatch(new Close(intval($value['fd'])));
            } else {
                ++$count;
            }
        }
        return $count;
    }
}
