<?php
namespace app\api;

use suframe\core\components\Config;

class Chart{

    public function service($params = []){
        $servers = Config::getInstance()->get('servers')->toArray();
        return $servers;
    }

}