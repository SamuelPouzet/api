<?php

namespace SamuelPouzet\Api\Controller\Factories;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Controller\AuthController;
use SamuelPouzet\Api\Service\AuthService;
use SamuelPouzet\Api\Service\IdentityService;

class AuthControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $authService = $container->get(AuthService::class);
        $identityService= $container->get(IdentityService::class);
        return new AuthController($authService, $identityService);
    }
}