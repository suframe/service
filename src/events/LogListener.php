<?php
/**
 * User: qian
 * Date: 2019/6/5 13:17
 */

namespace suframe\service\events;

use suframe\core\components\Config;
use suframe\core\components\log\LogConfig;
use suframe\core\components\register\Client as RegisterClient;
use suframe\core\components\rpc\SRpc;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

class TcpListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * 注册事件
     * @param EventManagerInterface $events
     * @param int $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $hasLog = Config::getInstance()->get('app.log');
        if ($hasLog) {
            $this->listeners[] = $events->attach('tcp.receive.after', [$this, 'receiveAfter'], $priority);
        }
    }

    public function receiveAfter(EventInterface $e)
    {
        $hasLog = $e->getParam('hasLog');
        if ($hasLog) {
            return $this->sendLog($e);
        }
    }

    private function sendLog(EventInterface $e)
    {
        $request = $e->getParam('request');
        $out = $e->getParam('out');
        if (is_string($request)) {
            $request = json_decode($request, true);
        }
        $request['_status'] = $out['status'] ?? 404;
        $request['_data'] = $out['data'] ?? null;
        SRpc::route('/sapps/Log')->write(LogConfig::TYPE_RPC, $request, 'rpc');
    }

}