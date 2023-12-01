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

use App\Codec\Decoder;
use App\Handle\HandleFactory;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

#[Listener]
class DecoderListener implements ListenerInterface
{
    private LoggerInterface $logger;

    public function __construct(private ContainerInterface $container)
    {
        $this->logger = $container->get(LoggerFactory::class)->get('decoder');
    }

    public function listen(): array
    {
        return [
            Decoder::class,
        ];
    }

    public function process(object $event): void
    {
        if ($event instanceof Decoder) {
            $frame = $event->decode();
            if ($frame) {
                $handle = HandleFactory::take($frame->getCmd());
                if (! is_null($handle)) {
                    $handle->run($frame, $event->getFd());
                } else {
                    var_dump('未找到命令解析器');
                }
            } else {
                var_dump('解码失败');
            }
        }
    }
}
