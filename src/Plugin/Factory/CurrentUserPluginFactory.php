<?php

namespace SamuelPouzet\Api\Plugin\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Plugin\CurrentUserPlugin;
use SamuelPouzet\Api\Service\IdentityService;
use SamuelPouzet\Api\Service\UserService;

class CurrentUserPluginFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $identityService = $container->get('identity.service');
        return new CurrentUserPlugin($identityService);
    }

}