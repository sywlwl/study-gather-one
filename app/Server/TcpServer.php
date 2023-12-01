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

use App\Codec\Decoder;
use App\Contract\AuthedChannel;
use App\Ws\WsAuthedChannelMemory;
use Hyperf\Contract\OnReceiveInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\ApplicationContext;
use Psr\EventDispatcher\EventDispatcherInterface;

class TcpServer implements OnReceiveInterface
{
    #[Inject]
    public AuthedChannel $authedChannel;

    public function __construct(protected EventDispatcherInterface $eventDispatcher)
    {
    }

    public function onReceive($server, int $fd, int $reactorId, string $data): void
    {
        $this->eventDispatcher->dispatch(new Decoder($data, $fd));
    }

    public function onConnect($server, $fd)
    {
        echo '连接', "\n";
        $wschannel = ApplicationContext::getContainer()->get(WsAuthedChannelMemory::class);
        $wschannel->send("aaaa", "123");
    }

    public function onClose($server, $fd)
    {
        echo '关闭', "\n";
        $this->authedChannel->removeChannelByFd($fd);
    }
}
