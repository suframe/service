<?php
return [
    'server' => [
        'listen' => '0.0.0.0',
        'port' => 9051,
    ],
    'swoole' => [
        'worker_num' => 1 //一般为cpu核数的1-4倍
    ]
];