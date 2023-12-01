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
use App\Cmd\ResponseLoginCmd;
use App\Cmd\ResponseTransactionRecordCmd;
use App\Codec\Frame;
use App\Constants\Cmd;
use App\Contract\AuthedChannel;
use App\Contract\HandleInterface;
use App\Tool\BCD;
use App\Tool\BIN;
use App\Tool\CP56Time2a;
use Hyperf\Di\Annotation\Inject;

/**
 * 交易记录处理.
 */
#[Handle]
class TransactionRecordHandle implements HandleInterface
{
    #[Inject]
    public AuthedChannel $authedChannel;

    public function __construct()
    {
        HandleFactory::register(Cmd::X3B, $this);
    }

    public function run(Frame $frame, int $fd)
    {
        $data = $frame->getData();

        $reader = BIN::newReader($data);

        $batchNo = BCD::bin2Bcd($reader->readSlice(16));

        $pile = BCD::bin2Bcd($reader->readSlice(7));

        $gun = BCD::bin2Bcd($reader->readSlice(1));

        // 开始时间
        $startTime = CP56Time2a::bin2DatetimeLE($reader->readSlice(7));
        // 结束时间
        $endTime = CP56Time2a::bin2DatetimeLE($reader->readSlice(7));

        $tipPrice = $reader->readIntLE();
        $tipPower = $reader->readIntLE();
        $tipLosePower = $reader->readIntLE();

        $peakPrice = $reader->readIntLE();
        $peakPower = $reader->readIntLE();
        $peakLosePower = $reader->readIntLE();

        $flatPrice = $reader->readIntLE();
        $flatPower = $reader->readIntLE();
        $flatLosePower = $reader->readIntLE();

        $valleyPrice = $reader->readIntLE();
        $valleyPower = $reader->readIntLE();
        $valleyLosePower = $reader->readIntLE();

        echo '这是 ', Cmd::getMessage($frame->getCmd()), "\n";
        echo $batchNo, "\n";
        echo $pile, "\n";
        echo $gun, "\n";
        echo $startTime->format('Y-m-d H:i:s.v'), "\n";
        echo $endTime->format('Y-m-d H:i:s.v'), "\n";
        echo $tipPrice, "\n";
        echo $tipPower, "\n";
        echo $tipLosePower, "\n";

        echo $peakPrice, "\n";
        echo $peakPower, "\n";
        echo $peakLosePower, "\n";

        echo $flatPrice, "\n";
        echo $flatPower, "\n";
        echo $flatLosePower, "\n";

        echo $valleyPrice, "\n";
        echo $valleyPower, "\n";
        echo $valleyLosePower, "\n";
        echo '====================', "\n";

        // 响应登录
        $resFrame = ResponseTransactionRecordCmd::make($batchNo, 0, $frame);

        // 需要发给客户端
        $this->authedChannel->send($pile, $resFrame);
    }
}
