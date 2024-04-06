<?php

namespace SamuelPouzet\Api\Plugin\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Plugin\ApiProblemPlugin;

class ApiProblemPluginFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new ApiProblemPlugin();
    }
}