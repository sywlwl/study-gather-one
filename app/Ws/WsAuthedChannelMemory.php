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
namespace App\Ws;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Memory\TableManager;
use Hyperf\Utils\ApplicationContext;
use Psr\EventDispatcher\EventDispatcherInterface;

class WsAuthedChannelMemory
{
    protected string $key = '';

    protected array $config = [];

    public function __construct(ConfigInterface $config, protected EventDispatcherInterface $eventDispatcher)
    {
        $this->config = $config->get('wschannel');
        $key = $this->config['type'];
        $this->key = $this->config[$key]['identifier'];
    }

    public function addChannel(string $uid, $fd): void
    {
        $table = TableManager::get($this->key);
        $table->set($uid, [
            'uid' => $uid,
            'fd' => strval($fd),
            'timestamp' => strval(time()),
        ]);
    }

    public function getChannel(string $uid): ?int
    {
        $table = TableManager::get($this->key);
        $ret = $table->get($uid, 'fd');
        return $ret ? intval($ret) : null;
    }

    public function getChannels(): array
    {
        $table = TableManager::get($this->key);
        $map = [];
        foreach ($table as $row) {
            $map[$row['uid']] = $row;
        }
        return $map;
    }

    public function removeChannel(string $uid): void
    {
        $table = TableManager::get($this->key);
        $table->del($uid);
    }

    public function removeChannelByFd(int $fd): void
    {
        $table = TableManager::get($this->key);
        foreach ($table as $row) {
            if ($fd == intval($row['fd'])) {
                $this->removeChannel(strval($row['uid']));
                break;
            }
        }
    }

    public function hasChannel(string $uid): bool
    {
        $table = TableManager::get($this->key);
        return $table->exist($uid);
    }

    public function send(string $uid, String $data): void
    {
        if ($this->hasChannel($uid)) {
            $fd = $this->getChannel($uid);
            $server = ApplicationContext::getContainer()->get(\Hyperf\WebSocketServer\Server::class);
            $server->getSender()->push($fd, $data);
//            $this->eventDispatcher->dispatch(new Encoder($frame, $fd));
        }
    }

}
