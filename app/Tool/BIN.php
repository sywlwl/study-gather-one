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

class BIN
{
    public static function dump(string $bin, $out = true)
    {
        $ret = [];
        for ($i = 0; $i < strlen($bin); ++$i) {
            // 1个字节
            $ret[] = strtoupper(bin2hex(strval($bin[$i])));
        }
        if ($out) {
            echo join(' ', $ret), "\n";
        } else {
            return join('', $ret);
        }
    }

    public static function newWriter(): Writer
    {
        return new Writer();
    }

    public static function newReader(string $bin): Reader
    {
        return new Reader($bin);
    }

    /**
     * 判断大小端.
     */
    public static function isLE(): bool
    {
        $ret = pack('s', 1);
        return bin2hex($ret[0]) == '01';
    }

    /**
     * 整型 转 bin.
     */
    public static function int2Bin(int $num): string
    {
        $ret = pack('i', $num);
        if (static::isLE()) {
            return strrev($ret);
        }
        return $ret;
    }

    /**
     * 整型 转 bin. 小端.
     */
    public static function int2BinLE(int $num): string
    {
        $ret = pack('i', $num);
        if (static::isLE()) {
            return $ret;
        }
        return strrev($ret);
    }

    /**
     * bin 转 整型.
     */
    public static function bin2Int(string $bin): int
    {
        if (static::isLE()) {
            $bin = strrev($bin);
        }
        $ret = unpack('i', $bin);
        return $ret[1];
    }

    /**
     * bin 转 整型. 小端.
     */
    public static function bin2IntLE(string $bin): int
    {
        if (! static::isLE()) {
            $bin = strrev($bin);
        }
        $ret = unpack('i', $bin);
        return $ret[1];
    }

    /**
     * 短整型 转 bin.
     */
    public static function short2Bin(int $num): string
    {
        $ret = pack('s', $num);
        if (static::isLE()) {
            return strrev($ret);
        }
        return $ret;
    }

    /**
     * 短整型 转 bin 小端.
     */
    public static function short2BinLE(int $num): string
    {
        $ret = pack('s', $num);
        if (static::isLE()) {
            return $ret;
        }
        return strrev($ret);
    }

    /**
     * bin 转 短整型.
     */
    public static function bin2Short(string $bin): int
    {
        if (static::isLE()) {
            $bin = strrev($bin);
        }
        $ret = unpack('s', $bin);
        return $ret[1];
    }

    /**
     * bin 转 短整型. 小端.
     */
    public static function bin2ShortLE(string $bin): int
    {
        if (! static::isLE()) {
            $bin = strrev($bin);
        }
        $ret = unpack('s', $bin);
        return $ret[1];
    }

    /**
     * 长整型 转 bin.
     */
    public static function long2Bin(int $num): string
    {
        $ret = pack('q', $num);
        if (static::isLE()) {
            return strrev($ret);
        }
        return $ret;
    }

    /**
     * 长整型 转 bin 小端.
     */
    public static function long2BinLE(int $num): string
    {
        $ret = pack('q', $num);
        if (static::isLE()) {
            return $ret;
        }
        return strrev($ret);
    }

    /**
     * bin 转 长整型.
     */
    public static function bin2Long(string $bin): int
    {
        if (static::isLE()) {
            $bin = strrev($bin);
        }
        $ret = unpack('q', $bin);
        return $ret[1];
    }

    /**
     * bin 转 长整型. 小端.
     */
    public static function bin2LongLE(string $bin): int
    {
        if (! static::isLE()) {
            $bin = strrev($bin);
        }
        $ret = unpack('q', $bin);
        return $ret[1];
    }

    /**
     * 浮点 转 bin.
     */
    public static function float2Bin(float $num): string
    {
        $ret = pack('f', $num);
        if (static::isLE()) {
            return strrev($ret);
        }
        return $ret;
    }

    /**
     * 浮点 转 bin 小端.
     */
    public static function float2BinLE(float $num): string
    {
        $ret = pack('f', $num);
        if (static::isLE()) {
            return $ret;
        }
        return strrev($ret);
    }

    /**
     * bin 转 浮点.
     * @param mixed $bin
     */
    public static function bin2Float($bin): float
    {
        if (static::isLE()) {
            $bin = strrev($bin);
        }
        $ret = unpack('f', $bin);
        return $ret[1];
    }

    /**
     * bin 转 浮点.
     * @param mixed $bin
     */
    public static function bin2FloatLE($bin): float
    {
        if (! static::isLE()) {
            $bin = strrev($bin);
        }
        $ret = unpack('f', $bin);
        return $ret[1];
    }

    /**
     * 双精度 转 bin.
     */
    public static function double2Bin(float $num): string
    {
        $ret = pack('d', $num);
        if (static::isLE()) {
            return strrev($ret);
        }
        return $ret;
    }

    /**
     * 双精度 转 bin 小端.
     */
    public static function double2BinLE(float $num): string
    {
        $ret = pack('d', $num);
        if (static::isLE()) {
            return $ret;
        }
        return strrev($ret);
    }

    /**
     * bin 转 双精度.
     * @param mixed $bin
     */
    public static function bin2Double($bin): float
    {
        if (static::isLE()) {
            $bin = strrev($bin);
        }
        $ret = unpack('d', $bin);
        return $ret[1];
    }

    /**
     * bin 转 双精度.
     * @param mixed $bin
     */
    public static function bin2DoubleLE($bin): float
    {
        if (! static::isLE()) {
            $bin = strrev($bin);
        }
        $ret = unpack('d', $bin);
        return $ret[1];
    }
}
