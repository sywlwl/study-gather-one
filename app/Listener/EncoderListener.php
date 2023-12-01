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
use App\Tool\BIN;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

#[Listener]
class EncoderListener implements ListenerInterface
{
    private LoggerInterface $logger;

    public function __construct(private ContainerInterface $container)
    {
        $this->logger = $container->get(LoggerFactory::class)->get('encoder');
    }

    public function listen(): array
    {
        return [
            Encoder::class,
        ];
    }

    public function process(object $event): void
    {
        if ($event instanceof Encoder) {
            $data = $event->encode();
            $server = ApplicationContext::getContainer()->get(\Swoole\Server::class);
            $authedChannel = ApplicationContext::getContainer()->get(AuthedChannel::class);
            BIN::dump($data);
            $ret = $server->send($event->getFd(), $data);
            var_dump($ret);
            if (! $ret) {
                $authedChannel->removeChannelByFd($event->getFd());
            }
        }
    }

}
