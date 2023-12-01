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
use App\Codec\Frame;
use App\Constants\Cmd;
use App\Contract\HandleInterface;
use App\Tool\BCD;
use App\Tool\BIN;

// 回复 停止充电
#[Handle]
class ReplyStopHandle implements HandleInterface
{
    public function __construct()
    {
        HandleFactory::register(Cmd::X35, $this);
    }

    public function run(Frame $frame, $fd)
    {
        echo '这是 ', Cmd::getMessage($frame->getCmd()), "\n";

        $data = $frame->getData();

        $reader = BIN::newReader($data);

        $pile = strval(BCD::bin2Bcd($reader->readSlice(7)));
        $gun = BCD::bin2Bcd($reader->readSlice(1));
        $result = BCD::bin2Bcd($reader->readSlice(1));
        $reason = BCD::bin2Bcd($reader->readSlice(1));

        echo $pile, "\n";
        echo $gun, "\n";
        echo $result, "\n";
        echo $reason, "\n";
        echo '=================', "\n";
    }
}
