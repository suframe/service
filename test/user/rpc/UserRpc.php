<?php

namespace app\rpc;

/**
 * Class DemoRpc
 * @package app\rpc
 */
class UserRpc
{

    /**
     * demo接口
     *
     * @param string $name
     * @return string
     */
    public function getList(string $name): array
    {
        return ['hello ' . $name];
    }

}