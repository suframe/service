<?php
namespace app\api;

use suframe\core\components\rpc\SRpc;

class Search{

    public function run(){
        return SRpc::route('/demo/UserRpc')->getList('summer!');
    }

}