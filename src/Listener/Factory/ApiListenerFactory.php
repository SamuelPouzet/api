<?php

namespace SamuelPouzet\Api\Listener\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Listener\RouteListener;

class ApiListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RouteListener
    {
        return new RouteListener();
    }

}