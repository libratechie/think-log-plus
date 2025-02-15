<?php

namespace Libratechie\ThinkLogPlus;

use think\Container;
use think\helper\Str;

class Log extends \think\Log
{
    protected $namespace = '\\Libratechie\\ThinkLogPlus\\driver\\';
    public function createDriver(string $name): Channel
    {
        $type = $this->resolveType($name);
        $method = 'create' . Str::studly($type) . 'Driver';
        $params = $this->resolveParams($name);
        if (method_exists($this, $method)) {
            return $this->$method(...$params);
        }
        $class = $this->resolveClass($type);
        $driver = $this->app->invokeClass($class, $params);

        $lazy = !$this->getChannelConfig($name, "realtime_write", false) && !$this->app->runningInConsole();
        $allow = array_merge($this->getConfig("level", []), $this->getChannelConfig($name, "level", []));

        return new Channel($name, $driver, $allow, $lazy, $this->app->event);
    }
}