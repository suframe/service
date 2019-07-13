suframe service
===============
基于swoole的轻量rpc微服务

## 主要功能

* 提供http rest接口
* 提供rpc接口
* rpc连接池
* 自动注册到suframe/proxy代理中心
* 自动生成rpc接口ide提示

## 约定

* 基于tcp协议提供服务，rest接口通过proxy代理访问，rpc通过 SRpc::route('路由')进行访问
* 接口path和api路径约定一致，不单独配置路由表


## 安装

~~~
composer require suframe/suframe-service
~~~

## 命名规范

遵循PSR-2命名规范和PSR-4自动加载规范。

## 参与开发

QQ群：904592189


## 版权信息

suframe遵循Apache2开源协议发布，并提供免费使用。

版权所有Copyright © 2019- by qian <330576744@qq.com>

All rights reserved。