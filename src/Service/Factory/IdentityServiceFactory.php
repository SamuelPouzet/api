<?php

namespace SamuelPouzet\Api\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\AuthTokenService;
use SamuelPouzet\Api\Service\IdentityService;
use SamuelPouzet\Api\Service\RoleService;

class IdentityServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $tokenService = $container->get(AuthTokenService::class);
        $roleService = $container->get(RoleService::class);
        return new IdentityService($tokenService, $roleService);
    }

}