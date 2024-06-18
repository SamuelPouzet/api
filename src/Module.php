<?php

namespace SamuelPouzet\Api;

use Laminas\Mvc\MvcEvent;
use Laminas\Session\SessionManager;
use SamuelPouzet\Api\Listener\RouteListener;

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
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();

        // The following line instantiates the SessionManager and automatically
        // makes the SessionManager the 'default' one.
        $sessionManager = $serviceManager->get(SessionManager::class);
    }
}