<?php
namespace app\api;

use app\compoents\log\Store;
use suframe\core\components\log\LogConfig;

class Log{

    /**
     * @return Store
     */
    protected function getStore(){
        return Store::getInstance();
    }

    public function request($params = []){
        return $this->find(LogConfig::TYPE_REQUEST, $params);
    }

    public function rpc($params = []){
        return $this->find(LogConfig::TYPE_RPC, $params);
    }

    public function sql($params = []){
        return $this->find(LogConfig::TYPE_SQL, $params);
    }

    public function exception($params = []){
        return $this->find(LogConfig::TYPE_EXCEPTION, $params);
    }

    public function debug($params = []){
        return $this->find(LogConfig::TYPE_DEBUG, $params);
    }

    private function find($type, $params = []){
        $limit = 5;
        $get = $params['get'];
        if(isset($get['page']) && is_numeric($get['page']) && ($get['page'] > 0)){
            $page = $get['page'];
        } else {
            $page = 1;
        }
        $skip = ($page - 1) * $limit;
        $list = $this->getStore()->$type()->find([], [
            'limit' => 5,
            'skip' => $skip,
            'sort' => [
                '_id' => -1,
            ]
        ]);
        $rs = [];
        foreach ($list as $item) {
            $rs[] = $item;
        }
        return $rs;
    }

    public function requestInfo(){
        return $this->getStore()->request()->findOne([], [
            'sort' => ['created_time' => -1]
        ]);
    }

}