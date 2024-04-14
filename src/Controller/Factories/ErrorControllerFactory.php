<?php

namespace SamuelPouzet\Api\Controller\Factories;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Controller\ErrorController;

class ErrorControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): ErrorController
    {
        return new ErrorController();
    }
}