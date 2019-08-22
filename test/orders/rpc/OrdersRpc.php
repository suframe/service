<?php

namespace app\rpc;

use suframe\core\components\swoole\Context;

/**
 * Class DemoRpc
 * @package app\rpc
 */
class OrdersRpc
{

    /**
     * demo接口
     *
     * @param string $name
     * @return string
     */
    public function getList(string $name): array
    {
        $id = Context::get('x_request_id');
        return ['hello ' . $name . ':' . $id];
    }

}