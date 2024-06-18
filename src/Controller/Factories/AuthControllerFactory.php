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
use SamuelPouzet\Api\Service\JwtService;

class AuthControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthController
    {
        $authService = $container->get('auth.service');
        $identityService = $container->get('identity.service');
        $tokenService = $container->get(AuthTokenService::class);
        $cooKieService = $container->get(CookieService::class);
        $jwtService = $container->get(JwtService::class);
        $form = $container->get('Config')['form']['auth.form'];
        return new AuthController($authService, $identityService, $tokenService, $cooKieService, $jwtService, $form);
    }
}