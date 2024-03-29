<?php
namespace suframe\service\components;

use Exception;
use suframe\core\components\Config;
use suframe\core\components\rpc\RpcUnPack;
use suframe\core\components\swoole\Context;
use suframe\core\traits\Singleton;

class Proxy
{
    use Singleton;
    /**
     * 服务代理转发
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function dispatch($data){
        $pack = new RpcUnPack($data);
        $path = $pack->get('path');
        $path = ltrim($path, '/');
        $apiName = explode('/', $path);
        $isRpc = false;
        if ($apiName[0] === 'summer') {
            $nameSpace = '\suframe\service\api\\';
        } else if($apiName[0] === 'rpc'){
            $isRpc = true;
            $nameSpace = Config::getInstance()->get('app.rpcNameSpace');
        } else {
            $nameSpace = Config::getInstance()->get('app.apiNameSpace');
        }
        array_shift($apiName);

        $className = array_pop($apiName);
        $className = ucfirst($className);
        $apiName[] = $className;
        $className = implode('\\', $apiName);
        $apiClass = $nameSpace . $className;

        if (class_exists($apiClass)) {
            $methodName = 'run';
        } else {
            $methodName = array_pop($apiName);
            $className = implode('\\', $apiName);
            $apiClass = $nameSpace . $className;
            if (!class_exists($apiClass)) {
                throw new Exception('api class not found:' . $apiClass);
            }
        }

        $api = new $apiClass;
        if (!method_exists($api, $methodName)) {
            throw new Exception('api method not found:' . $methodName);
        }
        $params = $pack->get();
        Context::put('request', $params);
        if($isRpc){
            $arguments = $params['arguments'] ?? [];
            $rs = $api->$methodName(...$arguments);
            return $rs;
        }

        $rs = $api->$methodName($params);
        return $rs;
    }

}
