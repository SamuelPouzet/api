<?php

namespace SamuelPouzet\Api\Service\Factory;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Entity\User;
use SamuelPouzet\Api\Manager\TokenManager;
use SamuelPouzet\Api\Service\AuthService;
use SamuelPouzet\Api\Service\IdentityService;
use SamuelPouzet\Api\Service\SessionService;

class AuthServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $identityService = $container->get('identity.service');
        $entityManager = $container->get(EntityManager::class);
        $sessionService = $container->get(SessionService::class);
        $userEntity = $this->getUserEntity($container->get('config'));
        return new AuthService($identityService, $entityManager, $sessionService, $userEntity);
    }

    protected function getUserEntity(array $config): string
    {
        if (!isset($config['entities']['user']) || !class_exists((string)$config['entities']['user'])) {
            return User::class;
        }
        return (string)$config['entities']['user'];
    }
}