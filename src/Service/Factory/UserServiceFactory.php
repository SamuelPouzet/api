<?php

namespace SamuelPouzet\Api\Service\Factory;

use Doctrine\ORM\EntityManager;
use Laminas\Http\Request;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\JwtService;
use SamuelPouzet\Api\Service\SessionService;
use SamuelPouzet\Api\Service\UserService;

class UserServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $jwtService = $container->get(JwtService::class);
        $sessionService = $container->get((SessionService::class));
        $entitymanager = $container->get(EntityManager::class);
        return new UserService($jwtService, $sessionService, $entitymanager);
    }

}