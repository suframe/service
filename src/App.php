<?php

namespace suframe\service;

use suframe\core\components\Config;
use suframe\core\components\console\SymfonyStyle;
use suframe\core\components\event\EventManager;
use suframe\core\components\net\tcp\Out;
use suframe\core\components\net\tcp\Server;
use suframe\core\components\register\Client as RegisterClient;
use suframe\core\traits\Singleton;
use suframe\service\components\Proxy;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class App
{
    use Singleton;

    protected $config = [];
    /**
     * @var SymfonyStyle
     */
    protected $io;
    /**
     * @var Proxy
     */
    protected $proxy;

    /**
     * @throws \Exception
     */
    public function run(InputInterface $input, OutputInterface $output) {
        $this->io = new SymfonyStyle($input, $output);
        $config = Config::getInstance();
        $tcp = new Server();
        $this->config = $config->get('tcp')->toArray();
		//设置代理
		$this->initProxy();
        //守护进程运行
        if (true === $input->hasParameterOption(['--daemon', '-d'], true)) {
            $this->config['swoole']['daemonize'] = 1;
        }
        //创建启动服务
        $tcp->create($this->config);
        EventManager::get()->trigger('tcp.run.before', $this, $tcp);
        $server = $tcp->getServer();
        $server->on('receive', [$this, 'onReceiveTcp']);
        $server->on('start', [$this, 'onStart']);
        $server->on('shutdown', [$this, 'onShutdown']);
        $server->start();
        EventManager::get()->trigger('tcp.run.after', $this, $tcp);
    }

    /**
     * 服务启动回调
     * @throws \Exception
     */
    public function onStart() {
        $this->io->success('ra server is running');
        $ip = swoole_get_local_ip();
        $listen = $this->config['server']['listen'] == '0.0.0.0' ? array_shift($ip) : $this->config['server']['listen'];
        $this->io->text('<info>ra server:</info> ' . $listen . ':' . $this->config['server']['port']);

        //注册服务
        go(function () use ($listen){
            RegisterClient::getInstance()->register([
                'path' => Config::getInstance()->get('app.path'),
                'ip' => $listen,
                'port' => $this->config['server']['port'],
            ]);
        });
    }

    /**
     * tcp请求回调
     * @param \Swoole\Server $server
     * @param $fd
     * @param $reactor_id
     * @param $data
     * @throws \Exception
     */
    public function onReceiveTcp(\Swoole\Server $server, $fd, $reactor_id, $data) {
        var_dump($data);
        EventManager::get()->trigger('tcp.request', $this, ['data' => &$data]);
        try {
            $out = $this->proxy->dispatch($data);
        } catch (\Exception $e) {
            return Out::error($server, $fd, $e->getMessage());
        }
        if (!$out) {
            return Out::notFound($server, $fd);
        }
        Out::success($server, $fd, $out);
        go(function () use ($data, $out){
            EventManager::get()->trigger('tcp.response.after', $this, [
                'request' => $data,
                'out' => $out,
            ]);
        });
    }

    /**
     * 服务结束
     */
    public function onShutdown() {
        EventManager::get()->trigger('tcp.shutDown', $this);
    }

	protected function initProxy(){
		//这里应该是action代理
		$this->proxy = new Proxy();
	}

}