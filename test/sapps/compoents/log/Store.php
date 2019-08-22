<?php

namespace app\compoents\log;

use MongoDB\Client as ClientMongo;
use suframe\core\components\Config;
use suframe\core\traits\Singleton;

/**
 * 日志存储，MongoDB
 * Class Store
 * @package app\compoents\log
 */
class Store
{
    use Singleton;

    /** @var \MongoDB\Database */
    protected $db;

    /**
     * Store constructor.
     * @param $uri
     * @param $collection
     * @throws \Exception
     */
    public function __construct()
    {
        $config = Config::getInstance()->get('log');
        if (!$config) {
            throw new \Exception('请先配置MongoDB');
        }
        $uri = $config->get('uri');
        $db = $config->get('db');
        $this->db = (new ClientMongo($uri))->$db;
    }

    /**
     * @return \MongoDB\Database
     */
    protected function getDb()
    {
        return $this->db;
    }

    /**
     * @return \MongoDB\Collection
     */
    public function request()
    {
        return $this->getDb()->request;
    }

    /**
     * @return \MongoDB\Collection
     */
    public function rpc()
    {
        return $this->getDb()->rpc;
    }

    /**
     * @return \MongoDB\Collection
     */
    public function sql()
    {
        return $this->getDb()->sql;
    }

    /**
     * @return \MongoDB\Collection
     */
    public function debug()
    {
        return $this->getDb()->debug;
    }

    /**
     * @return \MongoDB\Collection
     */
    public function exception()
    {
        return $this->getDb()->exception;
    }

}