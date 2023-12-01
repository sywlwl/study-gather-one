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

// 回复 启动充电
#[Handle]
class ReplyStartHandle implements HandleInterface
{

    public function __construct()
    {
        HandleFactory::register(Cmd::X33, $this);
    }

    public function run(Frame $frame, $fd)
    {
        echo '这是 ', Cmd::getMessage($frame->getCmd()), "\n";

        $data = $frame->getData();

        $reader = BIN::newReader($data);

        $batchNo = BCD::bin2Bcd($reader->readSlice(16));
        $pile = strval(BCD::bin2Bcd($reader->readSlice(7)));
        $gun = BCD::bin2Bcd($reader->readSlice(1));
        $result = BCD::bin2Bcd($reader->readSlice(1));
        $reason = BCD::bin2Bcd($reader->readSlice(1));

        echo $batchNo, "\n";
        echo $pile, "\n";
        echo $gun, "\n";
        echo $result, "\n";
        echo $reason, "\n";
        echo '=================', "\n";
    }
}
