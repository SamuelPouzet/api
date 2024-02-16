<?php

namespace SamuelPouzet\Api\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Manager\TokenManager;
use SamuelPouzet\Api\Manager\UserManager;
use SamuelPouzet\Api\Service\AuthService;
use SamuelPouzet\Api\Service\IdentityService;
use SamuelPouzet\Api\Service\SessionService;

class AuthServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $identityService = $container->get(IdentityService::class);
        $tokenManager = $container->get(TokenManager::class);
        $userManager = $container->get(UserManager::class);
        $sessionService = $container->get(SessionService::class);
        return new AuthService($identityService, $tokenManager, $userManager, $sessionService);
    }

    protected function configAuthDatabase(array $config): \PDO
    {
        try {
            $connexion = new $config['database']['adapter'](
                $config['database']['dsn'],
                $config['database']['username'],
                $config['database']['password'],
            );
            return $connexion;
        } catch(\Exception $e) {

        }
    }
}