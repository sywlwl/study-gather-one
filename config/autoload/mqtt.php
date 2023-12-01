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
return [
    'server' => env('MQTT_SERVER', 'localhost'),
    'port' => (int) env('MQTT_PORT', 1883),
    'topic' => env('MQTT_TOPIC', 'gather'),
    'username' => env('MQTT_USERNAME', null),
    'password' => env('MQTT_PASSWORD', null),
];
