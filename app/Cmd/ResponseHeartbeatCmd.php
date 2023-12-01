<?php

namespace App\Cmd;

use App\Codec\Frame;
use App\Constants\Cmd;
use App\Tool\BCD;
use App\Tool\BIN;

// 响应心跳
class ResponseHeartbeatCmd
{
    private static int $cmd = Cmd::X04;

    public static function make(string $pile, string $gun, Frame $reqFrame): Frame
    {
        $frame = new Frame();
        $frame->setCmd(static::$cmd)
            ->setEncryption(0)
            ->setSeq($reqFrame->getSeq());

        $writer = BIN::newWriter();

        $writer->writeBin(BCD::bcd2Bin($pile));
        $writer->writeBin(BCD::bcd2Bin($gun));
        $writer->writeChar(0);

        $frame->setData($writer->getBin());
        return $frame;
    }
}