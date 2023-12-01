<?php

namespace App\Cmd;

use App\Codec\Frame;
use App\Constants\Cmd;
use App\Tool\BCD;
use App\Tool\BIN;

// 响应登录
class ResponseLoginCmd
{
    private static int $cmd = Cmd::X02;

    public static function make(string $pile, int $loginResult, Frame $reqFrame): Frame
    {
        $frame = new Frame();
        $frame->setCmd(static::$cmd)
            ->setEncryption(0)
            ->setSeq($reqFrame->getSeq());

        $writer = BIN::newWriter();

        $writer->writeBin(BCD::bcd2Bin($pile));
        $writer->writeChar($loginResult);

        $frame->setData($writer->getBin());
        return $frame;
    }
}