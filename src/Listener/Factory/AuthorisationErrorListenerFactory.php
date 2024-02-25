<?php

namespace SamuelPouzet\Api\Listener\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Listener\AuthorisationErrorListener;

class AuthorisationErrorListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthorisationErrorListener
    {
        return new AuthorisationErrorListener();
    }
}