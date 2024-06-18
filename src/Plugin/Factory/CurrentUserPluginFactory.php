<?php

namespace SamuelPouzet\Api\Plugin\Factory;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Plugin\CurrentUserPlugin;
use SamuelPouzet\Api\Service\IdentityService;
use SamuelPouzet\Api\Service\UserService;

class CurrentUserPluginFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $userEntity = $container->get('Config')['entities']['user'];
        $identityService = $container->get('identity.service');
        $entityManager = $container->get(EntityManager::class);
        return new CurrentUserPlugin($identityService, $entityManager, $userEntity);
    }

}