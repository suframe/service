<?php
return [
    'server' => [
        'listen' => '0.0.0.0',
        'port' => 8080,
    ],
    'swoole' => [
        'open_length_check' => true,
        'package_length_type' => 'N',
        'package_length_offset' => 0,
        'package_body_offset' => 4,
        'package_max_length' => 1024 * 1024 * 3, //包大小为3M
        'buffer_output_size' => 1024 * 1024 * 3,//输出缓存区大小
        'log_file' => SUMMER_APP_ROOT . 'runtime/swoole.log',
        'pid_file' => SUMMER_APP_ROOT . 'runtime/swoole.pid'
    ],
];