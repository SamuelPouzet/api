<?php

namespace SamuelPouzet\Api\Manager\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Manager\TokenManager;

class TokenManagerFactory extends GeneralManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): TokenManager
    {
        parent::__invoke($container, $requestedName);
        return new TokenManager(self::$connexion);
    }

}