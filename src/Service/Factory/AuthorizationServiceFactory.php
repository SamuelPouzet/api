<?php

namespace SamuelPouzet\Api\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\AuthorizationService;
use SamuelPouzet\Api\Service\SessionService;

class AuthorizationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $config = $container->get('config')['authorization'];
        $sessionService = $container->get(SessionService::class);
        return new AuthorizationService($config, $sessionService);
    }
}