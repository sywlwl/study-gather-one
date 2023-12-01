<?php

namespace App\Contract;

use App\Codec\Frame;

interface HandleInterface
{
    public function __construct();
    public function run(Frame $frame, int $fd);
}