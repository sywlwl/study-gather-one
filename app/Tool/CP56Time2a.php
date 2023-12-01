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

class CP56Time2a
{
    public static function datetime2Bin(\DateTime $date): string
    {
        $year = intval($date->format('Y')) - 2000;
        $month = intval($date->format('m'));
        $day = intval($date->format('d'));
        $weekday = intval($date->format('w'));
        $hour = intval($date->format('H'));
        $minute = intval($date->format('i'));
        $milliseconds = intval($date->format('v')) + intval($date->format('s')) * 1000;

        return chr($year) .
            chr($month) .
            chr(($weekday << 5) | $day) .
            chr($hour) .
            chr($minute) .
            chr(($milliseconds >> 8) & 0xFF) .
            chr($milliseconds & 0xFF);
    }

    public static function datetime2BinLE(\DateTime $date): string
    {
        $ret = static::datetime2Bin($date);
        return strrev($ret);
    }

    public static function bin2Datetime(string $bin): \DateTime
    {
        $milliseconds = (ord($bin[5]) << 8) | ord($bin[6]);
        $minute = (ord($bin[4]) & 0x3F);
        $hour = (ord($bin[3]) & 0x1F);
        $day = (ord($bin[2]) & 0x1F);
        $month = (ord($bin[1]) & 0x0F);
        $year = (ord($bin[0]) & 0x7F);

        $date = new \DateTime();
        $date->setDate(2000 + $year, $month, $day);
        $date->setTime($hour, $minute, (int) floor($milliseconds / 1000), ($milliseconds % 1000) * 1000);
        return $date;
    }

    public static function bin2DatetimeLE(string $bin): \DateTime
    {
        $bin = strrev($bin);
        return static::bin2Datetime($bin);
    }
}
