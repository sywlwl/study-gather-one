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

use App\Annotation\Handle;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BeforeMainServerStart;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Memory\TableManager;
use Hyperf\Server\Event\MainCoroutineServerStart;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

#[Listener]
class BootListener implements ListenerInterface
{
    private LoggerInterface $logger;

    public function __construct(private ContainerInterface $container)
    {
        $this->logger = $container->get(LoggerFactory::class)->get('boot');
    }

    public function listen(): array
    {
        return [
            BeforeMainServerStart::class,
            MainCoroutineServerStart::class,
        ];
    }

    public function process(object $event): void
    {
        $annotationHandles = $this->getAnnotationHandles();
        $classes = array_keys($annotationHandles);
        foreach ($classes as $class) {
            $this->container->get($class);
        }

        // 初始化tcp channel
        $config = $this->container->get(ConfigInterface::class);
        $channelConfig = $config->get('channel');
        if ($channelConfig['type'] == 'memory') {
            $memory = $channelConfig['memory'];
            $table = TableManager::initialize($memory['identifier'], $memory['size'], 0.2);
            foreach ($memory['columns'] as $column) {
                $table->column($column[0], $column[1], $column[2]);
            }
            $table->create();
        }

        $wsChannelConfig = $config->get('wschannel');
        if ($wsChannelConfig['type'] == 'memory') {
            $memory = $wsChannelConfig['memory'];
            $table = TableManager::initialize($memory['identifier'], $memory['size'], 0.2);
            foreach ($memory['columns'] as $column) {
                $table->column($column[0], $column[1], $column[2]);
            }
            $table->create();
        }

    }

    private function getAnnotationHandles()
    {
        return AnnotationCollector::getClassesByAnnotation(Handle::class);
    }
}
