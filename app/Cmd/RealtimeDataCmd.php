<?php

namespace App\Cmd;

use App\Codec\Frame;
use App\Constants\Cmd;
use App\Server\Seq;
use App\Tool\BCD;
use App\Tool\BIN;

// 读取实时监测数据
class RealtimeDataCmd
{
    private static int $cmd = Cmd::X12;

    public static function make(string $pile, string $gun): Frame
    {
        $frame = new Frame();
        $frame->setCmd(static::$cmd)
            ->setEncryption(0)
            ->setSeq(Seq::getSeq());

        $writer = BIN::newWriter();

        $writer->writeBin(BCD::bcd2Bin($pile));
        $writer->writeBin(BCD::bcd2Bin($gun));

        $frame->setData($writer->getBin());
        return $frame;
    }
}