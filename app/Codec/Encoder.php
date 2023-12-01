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
namespace App\Codec;

use App\Tool\BCD;
use App\Tool\BIN;

class Encoder
{
    public const FIRST = 0x68;

    public function __construct(private Frame $frame, private int $fd)
    {
    }

    public function getFd()
    {
        return $this->fd;
    }

    public function encode(): string
    {
        $writer = BIN::newWriter();

        $writer->writeChar(self::FIRST);

        $data = $this->frame->getData();
        $length = strlen($data) + 4;
        $writer->writeChar($length);

        $writer->writeShortLE($this->frame->getSeq());

        $writer->writeChar($this->frame->getEncryption());

        $writer->writeChar($this->frame->getCmd());

        $writer->writeBin($data);

        // 校验
        $bin = $writer->getBin();
        $calcCrc = CRC::getCRC(substr($bin, 2));

        $writer->writeBin(BCD::bcd2Bin($calcCrc));

        return $writer->getBin();
    }
}
