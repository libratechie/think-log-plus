# Think-Log-Plus

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.0-8892BF.svg)](https://php.net/)
[![ThinkPHP Version](https://img.shields.io/badge/ThinkPHP-%3E%3D8.0-blue.svg)](https://www.thinkphp.cn/)
[![License](https://img.shields.io/badge/License-Apache--2.0-blue.svg)](LICENSE)

ThinkPHP 日志增强扩展包，提供 RabbitMQ 日志驱动支持，帮助构建分布式日志系统。

## 核心特性

- 原生支持 RabbitMQ 日志通道
- 兼容 ThinkPHP 8+ 日志系统
- 开箱即用的配置模板
- 支持消息队列持久化
- 灵活的环境变量配置
- 请求上下文信息自动采集

## 🚀 快速开始

### 安装

```bash
composer require libratechie/think-log-plus
```

### 基础配置

修改配置文件 `config/log.php`：

```php
return [
    // 默认日志记录通道
    'default'      => env('LOG_DEFAULT', 'rabbitmq'),
    // 日志通道列表
    'channels'     => [
        'rabbitmq' => [
            // 日志记录方式
            'type'           => 'RabbitMQ',
            // RabbitMQ 连接配置
            'host'           => env('LOG_RABBITMQ_HOST', 'localhost'),
            'port'           => env('LOG_RABBITMQ_PORT', 5672),
            'user'           => env('LOG_RABBITMQ_USER', 'guest'),
            'password'       => env('LOG_RABBITMQ_PASSWORD', 'guest'),
            'exchange'       => env('LOG_RABBITMQ_EXCHANGE', 'logs'),
            'routing_key'    => env('LOG_RABBITMQ_ROUTING_KEY', 'log'),
            // 队列名称
            'queue_name'    => env('LOG_RABBITMQ_QUEUE', false),
        ],
        // 其它日志通道配置
    ],
];
```

## 贡献指南
欢迎通过 Issue 或 PR 参与贡献，请遵循 Apache-2.0 开源协议。