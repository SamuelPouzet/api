<?php

namespace SamuelPouzet\Api\Service\Factory;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Manager\TokenManager;
use SamuelPouzet\Api\Service\AuthService;
use SamuelPouzet\Api\Service\IdentityService;
use SamuelPouzet\Api\Service\SessionService;

class AuthServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $identityService = $container->get(IdentityService::class);
        $entityManager = $container->get(EntityManager::class);
        $sessionService = $container->get(SessionService::class);
        return new AuthService($identityService, $entityManager, $sessionService);
    }
}