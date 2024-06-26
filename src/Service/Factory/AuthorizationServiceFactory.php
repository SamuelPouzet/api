<?php

namespace SamuelPouzet\Api\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\AuthorizationService;
use SamuelPouzet\Api\Service\JwtService;
use SamuelPouzet\Api\Service\RoleService;
use SamuelPouzet\Api\Service\SessionService;
use SamuelPouzet\Api\Service\UserService;

class AuthorizationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $config = $container->get('config')['authorization'];
        $userService = $container->get('user.service');
        $roleService = $container->get('role.service');
        $identityService = $container->get('identity.service');
        return new AuthorizationService($identityService, $userService, $roleService, $config);
    }
}