<?php

namespace App\Controller;

use App\Contract\AuthedChannel;
use App\Ws\WsAuthedChannelMemory;
use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Engine\WebSocket\Opcode;
use Hyperf\Utils\ApplicationContext;

class WebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    #[Inject]
    public WsAuthedChannelMemory $channel;

    public function onMessage($server, $frame): void
    {
        if($frame->opcode == Opcode::PING) {
            // 如果使用协程 Server，在判断是 PING 帧后，需要手动处理，返回 PONG 帧。
            // 异步风格 Server，可以直接通过 Swoole 配置处理，详情请见 https://wiki.swoole.com/#/websocket_server?id=open_websocket_ping_frame
            $server->push('', Opcode::PONG);
            return;
        }
        var_dump($server->header);
        //$server->push($frame->fd, 'Recv: ' . $frame->data);
//        $channel = ApplicationContext::getContainer()->get(WsAuthedChannelMemory::class);
        $this->channel->send("aaaa", $frame->data);
    }

    public function onClose($server, int $fd, int $reactorId): void
    {
//        $channel = ApplicationContext::getContainer()->get(WsAuthedChannelMemory::class);
        $this->channel->removeChannelByFd($fd);
        var_dump('closed');
    }

    public function onOpen($server, $request): void
    {
        var_dump($request->header);
        $server->push($request->fd, 'Opened');
//        $channel = ApplicationContext::getContainer()->get(WsAuthedChannelMemory::class);
//        'abc@wx',
//        'abc@web',
        $this->channel->addChannel('aaaa', $request->fd);
    }
}