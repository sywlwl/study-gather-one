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

class Frame
{
    private int $seq;

    private int $encryption;

    private int $cmd;

    private string $data;

    public function getSeq(): int
    {
        return $this->seq;
    }

    public function setSeq(int $seq): Frame
    {
        $this->seq = $seq;
        return $this;
    }

    public function getEncryption(): int
    {
        return $this->encryption;
    }

    public function setEncryption(int $encryption): Frame
    {
        $this->encryption = $encryption;
        return $this;
    }

    public function getCmd(): int
    {
        return $this->cmd;
    }

    public function setCmd(int $cmd): Frame
    {
        $this->cmd = $cmd;
        return $this;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): Frame
    {
        $this->data = $data;
        return $this;
    }
}
