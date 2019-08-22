<?php
/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require (__DIR__ . '/../vendor/autoload.php');
$loader->addPsr4('demo\\', __DIR__);
$serve = new \Swoole\Http\Server('0.0.0.0', 8080);

$serve->on('WorkerStart', function ($serv, $worker_id){
    $notify = inotify_init();
    inotify_add_watch($notify, __DIR__ . '/', IN_CREATE | IN_DELETE | IN_MODIFY);
    swoole_event_add($notify, function () use ($notify, $serv){
        $event = inotify_read($notify);
        if(!empty($serv)){
            $serv->reload();
        }
    });
});

$serve->on('request', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response){
   $response->write((new \demo\Demo())->test());
});

$serve->start();