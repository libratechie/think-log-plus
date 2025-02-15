<?php

// +----------------------------------------------------------------------
// | 日志设置
// +----------------------------------------------------------------------
return [
    // 默认日志记录通道
    'default'      => env('LOG_DEFAULT', 'file'),
    // 日志记录级别
    'level'        => [],
    // 日志类型记录的通道 ['error'=>'email',...]
    'type_channel' => [],
    // 关闭全局日志写入
    'close'        => false,
    // 全局日志处理 支持闭包
    'processor'    => null,

    // 日志通道列表
    'channels'     => [
        'file' => [
            // 日志记录方式
            'type'           => 'File',
            // 日志保存目录
            'path'           => '',
            // 单文件日志写入
            'single'         => false,
            // 独立日志级别
            'apart_level'    => [],
            // 最大日志文件数量
            'max_files'      => 0,
            // 使用JSON格式记录
            'json'           => false,
            // 日志处理
            'processor'      => null,
            // 关闭通道日志写入
            'close'          => false,
            // 日志输出格式化
            'format'         => '[%s][%s] %s',
            // 是否实时写入
            'realtime_write' => false,
        ],
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
