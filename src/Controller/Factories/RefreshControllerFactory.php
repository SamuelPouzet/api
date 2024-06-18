<?php

namespace SamuelPouzet\Api\Controller\Factories;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Controller\RefreshController;
use SamuelPouzet\Api\Service\AuthService;
use SamuelPouzet\Api\Service\AuthTokenService;
use SamuelPouzet\Api\Service\CookieService;
use SamuelPouzet\Api\Service\IdentityService;
use SamuelPouzet\Api\Service\JwtService;

class RefreshControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RefreshController
    {
        $authService = $container->get(AuthService::class);
        $identityService = $container->get(IdentityService::class);
        $tokenService = $container->get(AuthTokenService::class);
        $cookieService = $container->get(CookieService::class);
        $jwtService = $container->get(JwtService::class);

        return new RefreshController($authService, $identityService, $tokenService, $cookieService, $jwtService);
    }
}