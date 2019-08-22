<?php
namespace PHPSTORM_META {
    use suframe\core\components\rpc\SRpcInterface;
    override( SRpcInterface::route(0),
        map( [
            '/orders/OrdersRpc' => \app\runtime\rpc\orders\OrdersRpc::class,
            '/user/UserRpc' => \app\runtime\rpc\user\UserRpc::class,
        ]));
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