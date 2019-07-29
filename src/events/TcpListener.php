<?php
/**
 * User: qian
 * Date: 2019/6/5 13:17
 */

namespace suframe\service\events;

use suframe\core\components\Config;
use suframe\core\components\register\Client as RegisterClient;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

class TcpListener implements ListenerAggregateInterface {
	use ListenerAggregateTrait;

	/**
	 * 注册事件
	 * @param EventManagerInterface $events
	 * @param int $priority
	 */
	public function attach(EventManagerInterface $events, $priority = 1) {
		$this->listeners[] = $events->attach('tcp.start', [$this, 'start'], $priority);
	}

	/**
	 * 请求事件
	 * @param EventInterface $e
	 */
	public function start(EventInterface $e) {
        $this->inotify($e);
        //注册服务
        go(function () use ($e){
            RegisterClient::getInstance()->register([
                'path' => $e->getParam('path'),
                'ip' => $e->getParam('ip'),
                'port' => $e->getParam('port'),
            ]);
        });
	}

    /**
     * 监听文件变化，动态reload
     * @param EventInterface $e
     * @return bool
     */
	private function inotify(EventInterface $e){
        $isWatch = Config::getInstance()->get('tcp.server.watch');
        if(!$isWatch){
            return false;
        }
        $watches = Config::getInstance()->get('watch');
        if(!$watches){
            return false;
        }
        $server = $e->getParam('server');
        $notify = inotify_init();
        foreach ($watches as $watch) {
            if(!file_exists($watch)){
                continue;
            }
            inotify_add_watch($notify, $watch, IN_CREATE | IN_DELETE | IN_MODIFY);
        }
        swoole_event_add($notify, function () use ($notify, $server){
            inotify_read($notify);
            if(!empty($server)){
                $server->reload();
            }
        });
        echo "watching files\n";
    }

}