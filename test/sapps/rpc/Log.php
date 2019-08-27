<?php

namespace app\rpc;

use app\compoents\log\Store;
use MongoDB\InsertOneResult;
use suframe\core\components\Config;
use suframe\core\components\log\LogConfig;

/**
 * Class DemoRpc
 * @package app\rpc
 */
class Log
{

    /**
     * 写入日志
     * @param string $type
     * @param array $data
     * @param null $mark
     * @return string
     * @throws \Exception
     */
    public function write(string $type, $data = [], $mark = null)
    {
        $store = Store::getInstance();
        if (!method_exists($store, $type)) {
            echo 'not exist\n';
            return false;
        }
        //过滤,log自己就不用记录了
        if(($type == LogConfig::TYPE_REQUEST) && isset($data['request_uri'])){
            $selPath = Config::getInstance()->get('app.path');
            if(strpos($data['request_uri'], $selPath) === 0){
                return null;
            }
        }

        if ($mark) {
            $data['mark'] = $mark;
        }
        /** @var InsertOneResult $insertOneResult */
        $insertOneResult = $store->$type()->insertOne($data);
        $rs = $insertOneResult->getInsertedId();
        return $rs;
    }

}