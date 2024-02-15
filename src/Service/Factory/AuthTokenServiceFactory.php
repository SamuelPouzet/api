<?php

namespace SamuelPouzet\Api\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\AuthTokenService;

class AuthTokenServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $config = $container->get('Config')['Authentication']['provider'] ?? ['length' => 8];
        return new AuthTokenService($config);
    }
}