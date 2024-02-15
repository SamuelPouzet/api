<?php

namespace SamuelPouzet\Api\Manager\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Manager\UserManager;

class UserManagerFactory extends GeneralManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        parent::__invoke($container, $requestedName);
        return new UserManager(self::$connexion);
    }

}