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
namespace App\Tool;

class Reader
{
    // 索引
    private int $index = 0;

    public function __construct(private string $bin)
    {
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function getShort(): int
    {
        return BIN::bin2Short(substr($this->bin, $this->index, 2));
    }

    public function skip($length): void
    {
        $this->index += $length;
    }

    public function readSlice($length): string
    {
        $ret = substr($this->bin, $this->index, $length);
        $this->index += $length;
        return $ret;
    }

    public function readChar(): int
    {
        $ret = ord(substr($this->bin, $this->index, 1));
        ++$this->index;
        return $ret;
    }

    public function readShort(): int
    {
        $ret = $this->getShort();
        $this->index += 2;
        return $ret;
    }

    public function readShortLE(): int
    {
        $ret = BIN::bin2ShortLE(substr($this->bin, $this->index, 2));
        $this->index += 2;
        return $ret;
    }

    public function readInt(): int
    {
        $ret = BIN::bin2Int(substr($this->bin, $this->index, 4));
        $this->index += 4;
        return $ret;
    }

    public function readIntLE(): int
    {
        $ret = BIN::bin2IntLE(substr($this->bin, $this->index, 4));
        $this->index += 4;
        return $ret;
    }

    public function readLong(): int
    {
        $ret = BIN::bin2Long(substr($this->bin, $this->index, 8));
        $this->index += 8;
        return $ret;
    }

    public function readLongLE(): int
    {
        $ret = BIN::bin2LongLE(substr($this->bin, $this->index, 8));
        $this->index += 8;
        return $ret;
    }

    public function readFloat(): float
    {
        $ret = BIN::bin2Float(substr($this->bin, $this->index, 4));
        $this->index += 4;
        return $ret;
    }

    public function readFloatLE(): float
    {
        $ret = BIN::bin2FloatLE(substr($this->bin, $this->index, 4));
        $this->index += 4;
        return $ret;
    }
}
