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

// 对时设置
class CheckTimeCmd
{
    private static int $cmd = Cmd::X56;

    public static function make(string $pile): Frame
    {
        $frame = new Frame();
        $frame->setCmd(static::$cmd)
            ->setEncryption(0)
            ->setSeq(Seq::getSeq());

        $writer = BIN::newWriter();

        $writer->writeBin(BCD::bcd2Bin($pile));
        $writer->writeBin(CP56Time2a::datetime2BinLE(new \DateTime()));

        $frame->setData($writer->getBin());
        return $frame;
    }
}
