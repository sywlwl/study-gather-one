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
namespace App\Listener;

use App\Codec\Encoder;
use App\Contract\AuthedChannel;
use App\Server\Close;
use App\Tool\BIN;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

#[Listener]
class CloseListener implements ListenerInterface
{
    private LoggerInterface $logger;

    public function __construct(private ContainerInterface $container)
    {
        $this->logger = $container->get(LoggerFactory::class)->get('close');
    }

    public function listen(): array
    {
        return [
            Close::class,
        ];
    }

    public function process(object $event): void
    {
        if ($event instanceof Close) {
            $server = ApplicationContext::getContainer()->get(\Swoole\Server::class);
            $server->close($event->getFd(), false);
        }
    }
}
