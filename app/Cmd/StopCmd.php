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

// 停止充电
class StopCmd
{
    private static int $cmd = Cmd::X36;

    public static function make(
        string $batchNo,
        string $pile
    ): Frame {
        $frame = new Frame();
        $frame->setCmd(static::$cmd)
            ->setEncryption(0)
            ->setSeq(Seq::getSeq());

        $writer = BIN::newWriter();

        $writer->writeBin(BCD::bcd2Bin($batchNo));
        $writer->writeBin(BCD::bcd2Bin($pile));

        $frame->setData($writer->getBin());
        return $frame;
    }
}
