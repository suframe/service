<?php
namespace suframe\service\api\client;

use suframe\core\components\register\Client as ClientAlias;

class Notify
{

    public function run($params){
        go(function () use ($params){
            $command = $params['command'] ?? '';

            if(!$command || !method_exists($this, $command)){
                return false;
            }
            return $this->$command($params);
        });
        return true;
    }

    /**
     * @throws \Exception
     */
    protected function updateServers(){
        return ClientAlias::getInstance()->commandUpdateServers();
    }

}