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
namespace App\Handle;

use App\Constants\Cmd;
use App\Contract\HandleInterface;

class HandleFactory
{
    private static array $map = [];

    public static function register(int $cmd, HandleInterface $handle)
    {
        echo 'register handle ', Cmd::getMessage($cmd) , "\n";
        static::$map[$cmd] = $handle;
    }

    public static function take(int $cmd)
    {
        // var_dump(static::$map[$cmd]);
        return static::$map[$cmd] ?? null;
    }
}
