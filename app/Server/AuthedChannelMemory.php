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
namespace App\Server;

use Hyperf\Memory\TableManager;

class AuthedChannelMemory extends \App\Contract\AuthedChannel
{

    public function addChannel(string $pile, $fd): void
    {
        $table = TableManager::get($this->key);
        $table->set($pile, [
            'pile' => $pile,
            'fd' => strval($fd),
            'timestamp' => strval(time()),
        ]);
    }

    public function getChannel(string $pile): ?int
    {
        $table = TableManager::get($this->key);
        $ret = $table->get($pile, 'fd');
        return $ret ? intval($ret) : null;
    }

    public function getChannels(): array
    {
        $table = TableManager::get($this->key);
        $map = [];
        foreach ($table as $row) {
            $map[$row['pile']] = $row;
        }
        return $map;
    }

    public function removeChannel(string $pile): void
    {
        $table = TableManager::get($this->key);
        $table->del($pile);
    }

    public function removeChannelByFd(int $fd): void
    {
        $table = TableManager::get($this->key);
        foreach ($table as $row) {
            if ($fd == intval($row['fd'])) {
                $this->removeChannel(strval($row['pile']));
                break;
            }
        }
    }

    public function hasChannel(string $pile): bool
    {
        $table = TableManager::get($this->key);
        return $table->exist($pile);
    }
}
