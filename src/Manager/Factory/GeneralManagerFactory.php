<?php

namespace SamuelPouzet\Api\Manager\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Manager\TokenManager;
use SamuelPouzet\Api\Service\AuthService;

abstract class GeneralManagerFactory implements FactoryInterface
{
    protected static \PDO $connexion;

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $dbConfig = $container->get('config')['Authentication'];
        self::configAuthDatabase($dbConfig);
    }

    protected static function configAuthDatabase(array $config): void
    {
        self::$connexion = new $config['database']['adapter'](
            $config['database']['dsn'],
            $config['database']['username'],
            $config['database']['password'],
        );
    }
}