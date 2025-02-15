<?php

namespace Libratechie\ThinkLogPlus;

use think\Service as BaseService;

class Service extends BaseService
{
    public function register()
    {
        // 服务注册
        $this->app->bind('think\Log', Log::class);
    }
}