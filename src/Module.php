<?php

namespace SamuelPouzet\Api;

use Laminas\Mvc\MvcEvent;
use SamuelPouzet\Api\Listener\ApiListener;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include dirname(__DIR__) . '/config/module.config.php';
        return $config;
    }

    public function onBootstrap(MvcEvent $event): void
    {

    }
}