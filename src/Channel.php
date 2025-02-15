<?php

namespace Libratechie\ThinkLogPlus;

use Stringable;
use think\event\LogRecord;

class Channel extends \think\log\Channel
{
    public function record($msg, string $type = 'info', array $context = [], bool $lazy = true)
    {
        if ($this->close || (!empty($this->allow) && !in_array($type, $this->allow))) {
            return $this;
        }

        if ($msg instanceof Stringable) {
            $msg = $msg->__toString();
        }

        if (is_string($msg) && !empty($context)) {
            $replace = [];
            foreach ($context as $key => $val) {
                $replace['{' . $key . '}'] = $val;
            }

            $msg = strtr($msg, $replace);
        }

        if (!empty($msg) || 0 === $msg) {
            $this->log[] = ['timestamp' => microtime(), 'level' => $type, 'message' => $msg];
            if ($this->event) {
                $this->event->trigger(new LogRecord($type, $msg));
            }
        }

        if (!$this->lazy || !$lazy) {
            $this->save();
        }

        return $this;
    }
}