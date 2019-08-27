<?php
namespace PHPSTORM_META {
    use suframe\core\components\rpc\SRpcInterface;
    override( SRpcInterface::route(0),
        map( [
            '/log/Server' => \app\runtime\rpc\log\Server::class,
            '/orders/OrdersRpc' => \app\runtime\rpc\orders\OrdersRpc::class,
            '/sapps/Config' => \app\runtime\rpc\sapps\Config::class,
            '/sapps/Log' => \app\runtime\rpc\sapps\Log::class,
            '/summer/Log' => \app\runtime\rpc\summer\Log::class,
            '/user/UserRpc' => \app\runtime\rpc\user\UserRpc::class,
        ]));
}

namespace app\runtime\rpc\log;

interface Server
{

    /**
     * 写入日志
     * @param string $type
     * @param array $data
     * @param null $mark
     * @return string
     * @throws \Exception
     */
    public function write (string $type, $data, $mark);
    
}
namespace app\runtime\rpc\orders;

interface OrdersRpc
{

    /**
     * demo接口
     *
     * @param string $name
     * @return string
     */
    public function getList (string $name);
    
}
namespace app\runtime\rpc\sapps;

interface Config
{

    /**
     * 根据key获取配置
     * @param $key
     * @param array $params
     * @return array
     */
    public function search ($key, $params);
    
}
interface Log
{

    /**
     * 根据key获取配置
     * @param $key
     * @param array $params
     * @return array
     */
    public function search ($key, $params);
    
    /**
     * 写入日志
     * @param string $type
     * @param array $data
     * @param null $mark
     * @return string
     * @throws \Exception
     */
    public function write (string $type, $data, $mark);
    
}
namespace app\runtime\rpc\summer;

interface Log
{

    /**
     * 写入日志
     * @param string $type
     * @param array $data
     * @param null $mark
     * @return string
     * @throws \Exception
     */
    public function write (string $type, $data, $mark);
    
}
namespace app\runtime\rpc\user;

interface UserRpc
{

    /**
     * demo接口
     *
     * @param string $name
     * @return string
     */
    public function getList (string $name);
    
}