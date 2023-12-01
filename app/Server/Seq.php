<?php

namespace App\Server;

class Seq
{
    private static int $seq = 0;

    public static function getSeq(): int
    {
        if (static::$seq > 65535) {
            static::$seq = 0;
        }
        return static::$seq++;
    }
}