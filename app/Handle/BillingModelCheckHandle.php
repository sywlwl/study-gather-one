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
use App\Cmd\ResponseBillingModelCheckCmd;
use App\Cmd\ResponseBillingModelCmd;
use App\Codec\Frame;
use App\Constants\Cmd;
use App\Contract\AuthedChannel;
use App\Contract\HandleInterface;
use App\Tool\BCD;
use App\Tool\BIN;
use Hyperf\Di\Annotation\Inject;

/**
 * 计费模型验证.
 */
#[Handle]
class BillingModelCheckHandle implements HandleInterface
{
    #[Inject]
    public AuthedChannel $authedChannel;

    public function __construct()
    {
        HandleFactory::register(Cmd::X05, $this);
    }

    public function run(Frame $frame, int $fd)
    {
        $data = $frame->getData();

        $reader = BIN::newReader($data);

        $pile = BCD::bin2Bcd($reader->readSlice(7));
        // 计费模型编号
        $no = BCD::bin2Bcd($reader->readSlice(2));

        echo '这是 ', Cmd::getMessage($frame->getCmd()), "\n";
        echo $pile, "\n";
        echo $no, "\n";
        echo '====================', "\n";

        $resFrame = ResponseBillingModelCheckCmd::make($pile, $no, $frame);

        // 需要发给客户端
        $this->authedChannel->send($pile, $resFrame);
    }
}
