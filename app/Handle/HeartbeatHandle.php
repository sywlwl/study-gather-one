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
namespace App\Handle;

use App\Annotation\Handle;
use App\Cmd\ResponseHeartbeatCmd;
use App\Codec\Frame;
use App\Constants\Cmd;
use App\Contract\AuthedChannel;
use App\Contract\HandleInterface;
use App\Tool\BCD;
use App\Tool\BIN;
use Hyperf\Di\Annotation\Inject;

#[Handle]
class HeartbeatHandle implements HandleInterface
{
    #[Inject]
    public AuthedChannel $authedChannel;

    public function __construct()
    {
        HandleFactory::register(Cmd::X03, $this);
    }

    public function run(Frame $frame, $fd)
    {
        echo '这是 ', Cmd::getMessage($frame->getCmd()), "\n";
        $data = $frame->getData();

        $reader = BIN::newReader($data);

        $pile = strval(BCD::bin2Bcd($reader->readSlice(7)));

        $gun = BCD::bin2Bcd($reader->readSlice(1));

        $state = $reader->readChar();

        echo $pile, "\n";
        echo $gun, "\n";
        echo $state, "\n";
        echo '=================', "\n";

        $this->authedChannel->addChannel($pile, $fd);

        // 响应心跳
        $resFrame = ResponseHeartbeatCmd::make($pile, $gun, $frame);

        // 需要发给客户端
        $this->authedChannel->send($pile, $resFrame);
    }
}
