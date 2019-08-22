<?php
namespace app\api;

use suframe\core\components\rpc\SRpc;

class Search{

    public function hello(){
        return 'hello sufreame sbbb';
    }

    public function helloRpc(){
        return SRpc::route('/orders/OrdersRpc')->getList('summer!');
    }

}