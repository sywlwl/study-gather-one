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

// 响应计费模型验证
class ResponseBillingModelCheckCmd
{
    private static int $cmd = Cmd::X06;

    public static function make(string $pile, string $no, Frame $reqFrame): Frame
    {
        $frame = new Frame();
        $frame->setCmd(static::$cmd)
            ->setEncryption(0)
            ->setSeq($reqFrame->getSeq());

        $writer = BIN::newWriter();

        $writer->writeBin(BCD::bcd2Bin($pile));
        $writer->writeBin(BCD::bcd2Bin($no));
        $writer->writeChar(1);

        $frame->setData($writer->getBin());
        return $frame;
    }
}
