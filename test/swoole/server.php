<?php

use Swoole\Coroutine\Channel;

$serve = new \Swoole\Server('0.0.0.0', 9999);

$pool = new Channel(1);
$serve->on('receive', function ($ser, $fd, $reactor_id, $data)use($pool){
    var_dump($data);
    if($data == 'hello world'){
        if($pool->length()){
            $client = $pool->pop(99999);
        } else {

            $client = new swoole_client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);
            if (!$client->connect('127.0.0.1', 9999, -1))
            {
                exit("connect failed. Error: {$client->errCode}\n");
            }
        }

        $client->send("hello world22222\n");
        $data .= ' |  === ' . $client->recv();
        $pool->push($client);
    }
   $ser->send($fd, 'rec:' . $data);
});

$serve->start();
