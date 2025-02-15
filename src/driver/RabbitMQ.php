<?php

namespace Libratechie\ThinkLogPlus\driver;

use Libratechie\ThinkLogPlus\LogDriverTrait;
use think\contract\LogHandlerInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ implements LogHandlerInterface
{
    use LogDriverTrait;

    protected $config = [
        'time_format'  => 'c',
        'format'       => '[%s][%s] %s',
    ];

    protected $connection;
    protected $channel;
    protected $exchange;
    protected $routingKey;

    public function __construct(array $config)
    {
        $this->config = array_merge($this->config, $config);
        if (empty($this->config['format'])) {
            $this->config['format'] = '[%s][%s] %s';
        }
        $this->connection = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password']
        );

        $this->channel = $this->connection->channel();
        $this->exchange = $config['exchange'];
        $this->routingKey = $config['routing_key'];

        // 声明交换机
        $this->channel->exchange_declare($this->exchange, 'direct', false, false, false);

        if (!empty($this->config['queue_name'])) {
            // 声明队列
            $this->channel->queue_declare($this->config['queue_name'], false, true, false, false);
            // 绑定队列到交换机
            $this->channel->queue_bind(
                $this->config['queue_name'],
                $this->exchange,
                $this->routingKey
            );
        }
    }

    public function save(array $log): bool
    {
        // 请求信息
        $info = $this->getRequestInfo(\think\facade\Request::instance());
        $info['request_time'] = $this->formatRequestTime($info['request_time']);

        // 格式化请求信息
        foreach ($log as $message) {
            $message['timestamp'] = $this->formatRequestTime($message['timestamp']);
            $info['message'][] = $message;
        }

        // 发送消息
        $message = new AMQPMessage(json_encode($info), ['content_type' => 'application/json']);
        $this->channel->basic_publish($message, $this->exchange, $this->routingKey);

        return true;
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
