<?php

namespace SamuelPouzet\Api\Service\Factory;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\AuthTokenService;
use SamuelPouzet\Api\Service\CookieService;
use SamuelPouzet\Api\Service\JwtService;

class AuthTokenServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $config = $container->get('Config')['Authentication']['provider'] ?? ['length' => 8];
        $entityManager = $container->get(EntityManager::class);
        $jwtService = $container->get(JwtService::class);
        $cookieService = $container->get(CookieService::class);
        return new AuthTokenService($config, $entityManager, $jwtService, $cookieService);
    }
}