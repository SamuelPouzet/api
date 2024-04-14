<?php

namespace SamuelPouzet\Api\Controller\Factories;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Controller\LogoutController;
use SamuelPouzet\Api\Controller\RefreshController;
use SamuelPouzet\Api\Service\AuthService;
use SamuelPouzet\Api\Service\CookieService;
use SamuelPouzet\Api\Service\JwtService;
use SamuelPouzet\Api\Service\SessionService;

class LogoutControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): LogoutController
    {
        $authService = $container->get(AuthService::class);
        $cookieService = $container->get(CookieService::class);
        $jwtService = $container->get(JwtService::class);
        $sessionService = $container->get(SessionService::class);

        return new LogoutController($authService, $cookieService, $jwtService, $sessionService);
    }
}