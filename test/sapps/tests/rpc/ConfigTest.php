<?php

class ConfigTest extends \PHPUnit\Framework\TestCase
{
    public function testDemo()
    {
        $config = new \app\rpc\Config();
        echo '<pre>';
            print_r($config->search('/user'));
        echo '<pre>';exit;
        $this->assertTrue(1 + 1 == 2);
    }

}