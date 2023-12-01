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

class Decoder
{
    public const FIRST = 0x68;

    public function __construct(private string $bin, private int $fd)
    {
    }

    public function getFd()
    {
        return $this->fd;
    }

    public function decode(): ?Frame
    {
        $reader = BIN::newReader($this->bin);

        $first = $reader->readChar();
        if ($first == self::FIRST) {
            $crc = BCD::bin2Bcd(substr($this->bin, -2, 2));
//            $calcCrc = CRC::getCRC(substr($this->bin, 2, -2));
            // 校验
//            if ($crc == $calcCrc) {
                $length = $reader->readChar();

                $seq = $reader->readShortLE();

                $encryption = $reader->readChar();

                $cmd = $reader->readChar();

                $data = $reader->readSlice($length - 4);

                $frame = new Frame();
                $frame->setCmd($cmd)
                    ->setEncryption($encryption)
                    ->setSeq($seq)
                    ->setData($data);
                return $frame;
//            }
        }

        return null;
    }
}
