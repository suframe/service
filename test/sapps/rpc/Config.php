<?php

namespace app\rpc;

/**
 * 简易的config rpc接口
 * Class Config
 * @package app\rpc
 */
class Config
{

    /**
     * 根据key获取配置
     * @param $key
     * @param array $params
     * @return array
     */
    public function search($key, $params = [])
    {
        $common = $this->getConfig('common');
        $apps = $this->getConfig('apps', $key);
        return $apps + $common;
    }

    /**
     * 获取配置
     * @param $name
     * @param null $key
     * @return array
     */
    protected function getConfig($name, $key = null)
    {
        $path = SUMMER_APP_ROOT . 'config/apps/';

        $file = $path . $name . '.php';
        if (!file_exists($file)) {
            return [];
        }
        $rs = include($file);
        if ($key === null) {
            return $rs;
        }
        return $rs[$key] ?? [];
    }

}