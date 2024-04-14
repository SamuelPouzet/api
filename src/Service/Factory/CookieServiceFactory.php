<?php

namespace SamuelPouzet\Api\Service\Factory;

use Laminas\Http\Response;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\CookieService;

class CookieServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): CookieService
    {
        $response = new Response();
        return new CookieService($response);
    }
}