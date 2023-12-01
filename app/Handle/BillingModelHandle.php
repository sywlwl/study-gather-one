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
use App\Cmd\ResponseBillingModelCmd;
use App\Codec\Frame;
use App\Constants\Cmd;
use App\Contract\AuthedChannel;
use App\Contract\HandleInterface;
use App\Tool\BCD;
use App\Tool\BIN;
use Hyperf\Di\Annotation\Inject;

/**
 * 计费模型.
 */
#[Handle]
class BillingModelHandle implements HandleInterface
{
    #[Inject]
    public AuthedChannel $authedChannel;

    public function __construct()
    {
        HandleFactory::register(Cmd::X09, $this);
    }

    public function run(Frame $frame, int $fd)
    {
        $data = $frame->getData();

        $reader = BIN::newReader($data);

        $pile = BCD::bin2Bcd($reader->readSlice(7));

        echo '这是 ', Cmd::getMessage($frame->getCmd()), "\n";
        echo $pile, "\n";
        echo '====================', "\n";

        $time = [
            0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
            0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
            0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
            0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
            0, 0, 0, 0, 0, 0, 0, 0,
        ];

        $fee = [
            // 尖
            200000,
            10000,
            // 峰
            200000,
            10000,
            // 平
            200000,
            10000,
            // 谷
            200000,
            10000,
        ];

        $resFrame = ResponseBillingModelCmd::make($pile, $fee, $time, $frame);

        // 需要发给客户端
        $this->authedChannel->send($pile, $resFrame);
    }
}
