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

class BCD
{
    public static function bin2Bcd(string $bin): string
    {
        return BIN::dump($bin, false);
    }

    // 3F8CCCCD   3F 8C CC CD
    public static function bcd2Bin(string $bcd): string
    {
        $ret = '';
        for ($i = 0; $i < strlen($bcd); $i = $i + 2) {
            $hex = substr($bcd, $i, 2);
            $ret .= chr((int) base_convert($hex, 16, 10));
        }
        return $ret;
    }
}
