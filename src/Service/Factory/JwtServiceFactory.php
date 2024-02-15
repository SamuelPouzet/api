<?php

namespace SamuelPouzet\Api\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\JwtService;

class JwtServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $config = $container->get('config')['JWT'];
        return new JwtService($config);

    }
}