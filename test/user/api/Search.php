<?php

namespace app\api;

use suframe\core\components\Config;
use suframe\core\SF;

class Search
{

    public function hello()
    {
        return 'hello sufreame sbbb';
    }

    public function helloRpc()
    {
        return SF::rpc('/orders/OrdersRpc')->getList('summer!');
    }

    public function sappsConfig()
    {
        return Config::getInstance()->get('sapps')->toArray();
    }

}