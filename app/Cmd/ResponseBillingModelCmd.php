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
use App\Tool\BCD;
use App\Tool\BIN;

// 响应计费模型
class ResponseBillingModelCmd
{
    private static int $cmd = Cmd::X0A;

    public static function make(string $pile, array $fee, array $time, Frame $reqFrame): Frame
    {
        $frame = new Frame();
        $frame->setCmd(static::$cmd)
            ->setEncryption(0)
            ->setSeq($reqFrame->getSeq());

        $writer = BIN::newWriter();
        // 费率 100000  0.1
        $writer->writeBin(BCD::bcd2Bin($pile));
        $writer->writeBin(BCD::bcd2Bin('0100'));
        foreach ($fee as $f) {
            $writer->writeIntLE($f);
        }
        $writer->writeChar(0);

        foreach ($time as $t) {
            $writer->writeChar($t);
        }

        $frame->setData($writer->getBin());
        return $frame;
    }
}
