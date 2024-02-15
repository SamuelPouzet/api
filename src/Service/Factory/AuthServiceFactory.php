<?php

namespace SamuelPouzet\Api\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use mysql_xdevapi\Exception;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\AuthService;

class AuthServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $dbConfig = $container->get('config')['Authentication'];
        $connexion = $this->configAuthDatabase($dbConfig);
        return new AuthService($connexion);
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