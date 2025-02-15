<?php

namespace Libratechie\ThinkLogPlus\driver;

use Libratechie\ThinkLogPlus\LogDriverTrait;

class File extends \think\log\driver\File
{

    use LogDriverTrait;

    /**
     * 日志写入接口
     * @access public
     * @param array $log 日志信息
     * @return bool
     */
    public function save(array $log): bool
    {
        $destination = $this->getMasterLogFile();

        $path = dirname($destination);
        !is_dir($path) && mkdir($path, 0755, true);

        // 请求信息
        $requestInfo = $this->getRequestInfo(\think\facade\Request::instance());
        $requestInfo['request_time'] = $this->formatRequestTime($requestInfo['request_time']);
        $requestMessage = sprintf($this->config['format'], $requestInfo['request_time'], 'request', json_encode($requestInfo));

        $info[] = $requestMessage;
        foreach ($log as $message) {
            $time = $this->formatRequestTime($message['timestamp']);
            $type = $message['level'];
            $msg = $message['message'];
            if (!is_string($msg)) {
                $msg = var_export($msg, true);
            }
            $message = $this->config['json'] ?
                json_encode(['time' => $time, 'type' => $type, 'msg' => $msg], $this->config['json_options']) :
                sprintf($this->config['format'], $time, $type, $msg);

            if (true === $this->config['apart_level'] || in_array($type, $this->config['apart_level'])) {
                // 独立记录的日志级别
                $filename = $this->getApartLevelFile($path, $type);
                $this->write([$requestMessage, $message], $filename);
                continue;
            }
            $info[] = $message;
        }

        if ($info) {
            return $this->write($info, $destination);
        }

        return true;
    }

    /**
     * 日志写入
     * @access protected
     * @param array  $message     日志信息
     * @param string $destination 日志文件
     * @return bool
     */
    protected function write(array $message, string $destination): bool
    {
        // 检测日志文件大小，超过配置大小则备份日志文件重新生成
        $this->checkLogSize($destination);

        $info = [];

        foreach ($message as $msg) {
            $info[] = is_array($msg) ? implode(PHP_EOL, $msg) : $msg;
        }

        $message = implode(PHP_EOL, $info) . PHP_EOL;

        return error_log($message, 3, $destination);
    }
}