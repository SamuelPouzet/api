<?php

namespace SamuelPouzet\Api\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\SessionManager;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\SessionService;

class SessionServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $sessionContainer = $container->get(\Laminas\Session\Container::class);
        return new SessionService($sessionContainer);
    }
}