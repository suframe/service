<?php
/**
 * User: qian
 * Date: 2019/6/5 13:17
 */

namespace suframe\service\events;

use suframe\core\components\Config;
use suframe\core\components\log\Log;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

class LogListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $logRoute;
    /**
     * 注册事件
     * @param EventManagerInterface $events
     * @param int $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->logRoute = Config::getInstance()->get('sapps.log');
        if ($this->logRoute) {
            $this->listeners[] = $events->attach('tcp.receive.after', [$this, 'receiveAfter'], $priority);
        }
    }

    public function receiveAfter(EventInterface $e)
    {
        $request = $e->getParam('request');
        $out = $e->getParam('out');
        if (is_string($request)) {
            $request = json_decode($request, true);
        }
        $request['_status'] = $out['status'] ?? 404;
        $request['_data'] = $out['data'] ?? null;
        Log::getInstance()->rpc($request['path'], $request, 'rpc');
    }

}