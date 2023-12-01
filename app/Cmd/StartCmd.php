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
namespace App\Cmd;

use App\Codec\Frame;
use App\Constants\Cmd;
use App\Server\Seq;
use App\Tool\BCD;
use App\Tool\BIN;
use App\Tool\CP56Time2a;

// 开始充电
class StartCmd
{
    private static int $cmd = Cmd::X34;

    public static function make(
        string $batchNo,
        string $pile,
        string $gun,
        string $logicCard,
        string $physicalCard,
        int $balance
    ): Frame {
        $frame = new Frame();
        $frame->setCmd(static::$cmd)
            ->setEncryption(0)
            ->setSeq(Seq::getSeq());

        $writer = BIN::newWriter();

        $writer->writeBin(BCD::bcd2Bin($batchNo));
        $writer->writeBin(BCD::bcd2Bin($pile));
        $writer->writeBin(BCD::bcd2Bin($gun));
        $writer->writeBin(BCD::bcd2Bin($logicCard));
        $writer->writeBin(BCD::bcd2Bin($physicalCard));
        // 10000
        $writer->writeIntLE($balance);

        $frame->setData($writer->getBin());
        return $frame;
    }
}
