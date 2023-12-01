<?php

return [
    'type' => 'memory', // redis
    'timeout' => 20, // 秒
    'memory' => [
        'identifier' => 'wschannels',
        'size' => 10000 * 45,
        'columns' => [
            ['uid', \Swoole\Table::TYPE_STRING, 20],
            ['fd', \Swoole\Table::TYPE_STRING, 20],
            ['timestamp', \Swoole\Table::TYPE_STRING, 20],
        ],
    ],
    'redis' => [
        'identifier' => 'channels',
    ],
];