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

class Writer
{
    // 二进制字符串
    private string $bin = '';

    // 索引
    private int $index = 0;

    public function getBin(): string
    {
        return $this->bin;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function writeBin(string $bin)
    {
        $this->bin .= $bin;
        $this->index += strlen($bin);
    }

    public function writeChar(int $num): void
    {
        $this->bin .= chr($num);
        ++$this->index;
    }

    public function writeShort(int $num): void
    {
        $this->bin .= BIN::short2Bin($num);
        $this->index += 2;
    }

    public function writeShortLE(int $num): void
    {
        $this->bin .= BIN::short2BinLE($num);
        $this->index += 2;
    }

    public function writeInt(int $num): void
    {
        $this->bin .= BIN::int2Bin($num);
        $this->index += 4;
    }

    public function writeIntLE(int $num): void
    {
        $this->bin .= BIN::int2BinLE($num);
        $this->index += 4;
    }

    public function writeLong(int $num): void
    {
        $this->bin .= BIN::long2Bin($num);
        $this->index += 8;
    }

    public function writeLongLE(int $num): void
    {
        $this->bin .= BIN::long2BinLE($num);
        $this->index += 8;
    }

    public function writeFloat(float $num): void
    {
        $this->bin .= BIN::float2Bin($num);
        $this->index += 4;
    }

    public function writeFloatLE(float $num): void
    {
        $this->bin .= BIN::float2BinLE($num);
        $this->index += 4;
    }
}
