<?php

namespace SamuelPouzet\Api\Controller\Factories;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Controller\AuthController;
use SamuelPouzet\Api\Entity\User;
use SamuelPouzet\Api\Service\AuthService;
use SamuelPouzet\Api\Service\AuthTokenService;
use SamuelPouzet\Api\Service\CookieService;
use SamuelPouzet\Api\Service\IdentityService;

class AuthControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthController
    {
        $authService = $container->get(AuthService::class);
        $identityService = $container->get('identity.service');
        $tokenService = $container->get(AuthTokenService::class);
        $cooKieService = $container->get(CookieService::class);
        return new AuthController($authService, $identityService, $tokenService, $cooKieService);
    }
}