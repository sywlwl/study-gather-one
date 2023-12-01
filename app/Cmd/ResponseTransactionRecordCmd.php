<?php

namespace App\Cmd;

use App\Codec\Frame;
use App\Constants\Cmd;
use App\Tool\BCD;
use App\Tool\BIN;

// 响应交易记录确认
class ResponseTransactionRecordCmd
{
    private static int $cmd = Cmd::X40;

    public static function make(string $batchNo, int $result, Frame $reqFrame): Frame
    {
        $frame = new Frame();
        $frame->setCmd(static::$cmd)
            ->setEncryption(0)
            ->setSeq($reqFrame->getSeq());

        $writer = BIN::newWriter();

        $writer->writeBin(BCD::bcd2Bin($batchNo));
        $writer->writeChar($result);
        $frame->setData($writer->getBin());
        return $frame;
    }
}