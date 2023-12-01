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

/**
 * 实时数据处理.
 */
#[Handle]
class RealtimeDataHandle implements HandleInterface
{
    public function __construct()
    {
        HandleFactory::register(Cmd::X13, $this);
    }

    public function run(Frame $frame, int $fd)
    {
        $data = $frame->getData();

        $reader = BIN::newReader($data);

        // 流水号
        $batchNo = BCD::bin2Bcd($reader->readSlice(16));

        // 桩号
        $pile = BCD::bin2Bcd($reader->readSlice(7));

        // 枪号
        $gun = BCD::bin2Bcd($reader->readSlice(1));

        // 状态
        $state = $reader->readChar();

        // 归位
        $home = $reader->readChar();

        // 插枪
        $insert = $reader->readChar();

        // 电压
        $outputVoltage = $reader->readShortLE();

        // 电流
        $outputCurrent = $reader->readShortLE();

        $reader->skip(1);
        $reader->skip(8);
        $reader->skip(1);
        $reader->skip(1);

        // 累计充电时间
        $duration = $reader->readShortLE();

        $reader->skip(2);
        $reader->skip(4);
        $reader->skip(4);

        // 已充金额
        $amount = $reader->readIntLE();

        echo '这是 ', Cmd::getMessage($frame->getCmd()), "\n";
        echo $batchNo, "\n";
        echo $pile, "\n";
        echo $gun, "\n";
        echo $state, "\n";
        echo $home, "\n";
        echo $insert, "\n";
        echo $outputVoltage, "\n";
        echo $outputCurrent, "\n";
        echo $duration, "\n";
        echo $amount, "\n";
        echo '====================', "\n";
    }
}
