<?php
return [
    'server' => [
        'listen' => '0.0.0.0',
        'port' => 8081,
    ],
    'swoole' => [
        'worker_num' => 2 //一般为cpu核数的1-4倍
    ]
];