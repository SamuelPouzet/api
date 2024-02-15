<?php

namespace SamuelPouzet\Api\Listener\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Listener\AuthorizationListener;
use SamuelPouzet\Api\Service\AuthorizationService;

class AuthorizationListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $authorizationService = $container->get(AuthorizationService::class);
        return new AuthorizationListener($authorizationService);
    }
}