<?php

namespace Libratechie\ThinkLogPlus;

use think\Request;

trait LogDriverTrait
{
    /**
     * 获取请求信息并返回 JSON 格式的数据
     *
     * @param Request $request ThinkPHP 请求对象
     * @return array
     */
    public function getRequestInfo(Request $request): array
    {
        // 获取环境变量
        $env = getenv('APP_ENV') ?: 'unknown';

        // 获取请求方法
        $method = $request->method();

        // 获取请求协议
        $scheme = $request->scheme();

        // 获取端口
        $port = $request->port();

        // 获取主机名
        $host = $request->host();

        // 获取域名
        $domain = $request->domain();

        // 获取基础文件
        $baseFile = $request->baseFile();

        // 获取基础 URL
        $baseUrl = $request->baseUrl();

        // 获取完整 URL
        $url = $request->url(true);

        // 获取应用名称
        $app = app('http')->getName();

        // 获取控制器名称
        $controller = $request->controller();

        // 获取操作名称
        $action = $request->action();

        // 获取客户端 IP 地址
        $ip = $request->ip();

        // 获取请求时间
        $requestTime = $request->time(true);
        $requestTimeFormatted = sprintf("%f00 %d", fmod($requestTime, 1), floor($requestTime));

        // 获取 GET 参数
        $requestGetParas = $request->get();

        // 获取 POST 参数
        $requestPostParas = $request->post();

        // 返回格式化后的数据
        return [
            'env' => $env,
            'method' => $method,
            'scheme' => $scheme,
            'port' => $port,
            'host' => $host,
            'domain' => $domain,
            'base_file' => $baseFile,
            'base_url' => $baseUrl,
            'url' => $url,
            'app' => $app,
            'controller' => $controller,
            'action' => $action,
            'ip' => $ip,
            'request_time' => $requestTimeFormatted,
            'request_get_paras' => $requestGetParas,
            'request_post_paras' => $requestPostParas,
        ];
    }

    public function formatRequestTime($requestTime): string
    {
        return \DateTime::createFromFormat('0.u00 U', $requestTime)
            ->setTimezone(new \DateTimeZone(date_default_timezone_get()))
            ->format($this->config['time_format']);
    }
}
