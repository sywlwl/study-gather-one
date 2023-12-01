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
namespace App\Process;

use App\Cmd\StartCmd;
use App\Cmd\StopCmd;
use App\Contract\AuthedChannel;
use Hyperf\Config\Annotation\Value;
use Hyperf\Process\AbstractProcess;
use Hyperf\Process\Annotation\Process;

#[Process(name: 'mqtt-subscribe')]
class MqttSubscribeProcess extends AbstractProcess
{
    #[Value('mqtt.server')]
    private string $server;

    #[Value('mqtt.port')]
    private int $port;

    #[Value('mqtt.topic')]
    private string $topic;

    #[Value('mqtt.username')]
    private ?string $username;

    #[Value('mqtt.password')]
    private ?string $password;

    public function handle(): void
    {
        $authedChannel = $this->container->get(AuthedChannel::class);
        $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings())
            ->setUsername($this->username)
            ->setPassword($this->password);

        $mqtt = new \PhpMqtt\Client\MqttClient($this->server, $this->port);
        $mqtt->connect($connectionSettings, true);
        $mqtt->subscribe($this->topic, function ($topic, $message, $retained, $matchedWildcards) use ($authedChannel) {
            echo sprintf("Received message on topic [%s]: %s\n", $topic, $message);
            $json = @json_decode($message, true);
            if (is_array($json) && isset($json['cmd'])) {
                switch ($json['cmd']) {
                    case 'start':
                        // 启动充电
                        $pile = '67569755522001';
                        $gun = '01';
                        $batchNo = '32010200000000111511161555350260';
                        $logicCard = '0000001000000573';
                        $physicalCard = '00000000D14B0A54';
                        $balance = 1000;

                        $frame = StartCmd::make($batchNo, $pile, $gun, $logicCard, $physicalCard, $balance);
                        $authedChannel->send($pile, $frame);
                        break;
                    case 'stop':
                        // 停止充电
                        $pile = '67569755522001';
                        $gun = '01';

                        $frame = StopCmd::make($pile, $gun);
                        $authedChannel->send($pile, $frame);
                        break;
                }
            }
        }, 0);
        $mqtt->loop(true);
        $mqtt->disconnect();
    }
}
