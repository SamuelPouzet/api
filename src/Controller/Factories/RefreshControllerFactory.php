<?php

namespace SamuelPouzet\Api\Controller\Factories;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Controller\RefreshController;
use SamuelPouzet\Api\Service\AuthService;

class RefreshControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RefreshController
    {
        $authService = $container->get(AuthService::class);

        return new RefreshController($authService);
    }
}