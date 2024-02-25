<?php

namespace SamuelPouzet\Api\Service\Factory;

use Doctrine\ORM\EntityManager;
use \Laminas\Cache\Storage\Adapter\Filesystem;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\RoleService;

class RoleServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RoleService
    {
        return new RoleService(
            $container->get('FilesystemCache'),
            $container->get('doctrine.entitymanager.orm_default')
        );
    }
}