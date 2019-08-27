<?php
/**
 * User: qian
 * Date: 2019/8/26 13:17
 */

namespace suframe\service\events;

use suframe\core\components\Config;
use suframe\core\components\rpc\SRpc;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

class ConfigListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $configRoute;
    /**
     * 注册事件
     * @param EventManagerInterface $events
     * @param int $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->configRoute = Config::getInstance()->get('sapps.config');
        if ($this->configRoute) {
            $this->listeners[] = $events->attach('tcp.run.before', [$this, 'tcpRunBefore'], $priority);
        }
    }

    /**
     * 自动拉去远程配置
     * @param EventInterface $e
     */
    public function tcpRunBefore(EventInterface $e)
    {
        $rs = SRpc::route($this->configRoute['path'])->search($this->configRoute['key']);
        if(!$rs){
            return ;
        }
        $config = new Config(['sapps' => ['sapps' => $rs]]);
        Config::getInstance()->merge($config);
    }

}