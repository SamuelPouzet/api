<?php

namespace SamuelPouzet\Api;

use Application\Controller\IndexController;
use Doctrine\DBAL\Driver\PDO\MySQL\Driver;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Laminas\Cache\Storage\Adapter\Filesystem;
use Laminas\Router\Http\Literal;
use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\HttpUserAgent;
use Laminas\Session\Validator\RemoteAddr;
use SamuelPouzet\Api\Controller\AuthController;
use SamuelPouzet\Api\Controller\ErrorController;
use SamuelPouzet\Api\Controller\Factories\AuthControllerFactory;
use SamuelPouzet\Api\Controller\Factories\ErrorControllerFactory;
use SamuelPouzet\Api\Controller\Factories\RefreshControllerFactory;
use SamuelPouzet\Api\Controller\RefreshController;
use SamuelPouzet\Api\Listener\ApiListener;
use SamuelPouzet\Api\Listener\AuthenticationErrorListener;
use SamuelPouzet\Api\Listener\AuthorisationErrorListener;
use SamuelPouzet\Api\Listener\AuthorizationListener;
use SamuelPouzet\Api\Listener\ConstantListener;
use SamuelPouzet\Api\Listener\Factory\ApiListenerFactory;
use SamuelPouzet\Api\Listener\Factory\AuthenticationErrorListenerFactory;
use SamuelPouzet\Api\Listener\Factory\AuthorisationErrorListenerFactory;
use SamuelPouzet\Api\Listener\Factory\AuthorizationListenerFactory;
use SamuelPouzet\Api\Listener\Factory\ConstantsListenerFactory;
use SamuelPouzet\Api\Manager\Factory\TokenManagerFactory;
use SamuelPouzet\Api\Manager\Factory\UserManagerFactory;
use SamuelPouzet\Api\Manager\TokenManager;
use SamuelPouzet\Api\Manager\UserManager;
use SamuelPouzet\Api\Plugin\ApiProblemPlugin;
use SamuelPouzet\Api\Plugin\Factory\ApiProblemPluginFactory;
use SamuelPouzet\Api\Service\AuthorizationService;
use SamuelPouzet\Api\Service\AuthService;
use SamuelPouzet\Api\Service\AuthTokenService;
use SamuelPouzet\Api\Service\Factory\AuthorizationServiceFactory;
use SamuelPouzet\Api\Service\Factory\AuthServiceFactory;
use SamuelPouzet\Api\Service\Factory\AuthTokenServiceFactory;
use SamuelPouzet\Api\Service\Factory\IdentityServiceFactory;
use SamuelPouzet\Api\Service\Factory\JwtServiceFactory;
use SamuelPouzet\Api\Service\Factory\RoleServiceFactory;
use SamuelPouzet\Api\Service\Factory\SessionServiceFactory;
use SamuelPouzet\Api\Service\IdentityService;
use SamuelPouzet\Api\Service\JwtService;
use SamuelPouzet\Api\Service\RoleService;
use SamuelPouzet\Api\Service\SessionService;

return [
    'router' => [
        'routes' => [
            'auth' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/auth',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'refresh' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/refresh',
                    'defaults' => [
                        'controller' => RefreshController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'JWT' => [
        'bearer' => 'thissecretmustbeoverriden',
    ],
    'listeners' => [
        ApiListener::class,
        AuthorizationListener::class,
        // AuthorisationErrorListener::class,
    ],
    'controllers' => [
        'factories' => [
            AuthController::class => AuthControllerFactory::class,
            ErrorController::class => ErrorControllerFactory::class,
            RefreshController::class => RefreshControllerFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            ApiProblemPlugin::class => ApiProblemPluginFactory::class,
        ],
        'aliases' => [
            'apiProblem' => ApiProblemPlugin::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            ApiListener::class => ApiListenerFactory::class,
            AuthorizationListener::class => AuthorizationListenerFactory::class,
            AuthorizationService::class => AuthorizationServiceFactory::class,
            AuthorisationErrorListener::class => AuthorisationErrorListenerFactory::class,
            AuthService::class => AuthServiceFactory::class,
            AuthTokenService::class => AuthTokenServiceFactory::class,
            IdentityService::class => IdentityServiceFactory::class,
            JwtService::class => JwtServiceFactory::class,
            RoleService::class => RoleServiceFactory::class,
            SessionService::class => SessionServiceFactory::class,
            TokenManager::class => TokenManagerFactory::class,
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'Authentication' => [
        'database' => [
            'adapter'  => \PDO::class,
            'dsn'      => 'mysql:host=localhost;dbname=api',
            'username' => 'root',
            'password' => '0000',
        ],
        'provider' => [
            'length' => 16,
        ]
    ],
    'authorization' => [
        'allowedByDefault' => false,
        'controllers' => [
            IndexController::class => [
                'get' => [
                    'allowed' => true,
                    'roles' => ['role.admin'],
                ],
                'post' => [
                    'allowed' => true,
                    'roles' => '*',
                ],
            ],
            ErrorController::class => [
                'get' => [
                    'allowed' => true,
                    'roles' => '*',
                ],
                'post' => [
                    'allowed' => true,
                    'roles' => '*',
                ],
            ],
            AuthController::class => [
                'post' => [
                    'allowed' => true,
                    'roles' => '*',
                ],
            ],
            RefreshController::class => [
                'post' => [
                    'allowed' => true,
                    'roles' => '*',
                ],
            ],
        ],
    ],
    // Session configuration.
    'session_config' => [
        'cookie_lifetime'     => 60*60*1, // Session cookie will expire in 1 hour.
        'gc_maxlifetime'      => 60*60*24*30, // How long to store session data on server (for 1 month).
        'remember_me_seconds' =>  60*60*24*30, // How long to store remember_me session
        'cookie_secure' => false,
        'save_path' => dirname(__DIR__, 1) . '/data/session',
        'use_cookies' => true,
    ],
    // Session manager configuration.
    'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    'session_containers' => [
        \Laminas\Session\Container::class,
    ],
    'caches' => [
        'FilesystemCache' => [
            'adapter' => Filesystem::class,
            'options' => [
                'cache_dir' => dirname(__DIR__, 1) . '/data/cache',
                // Store cached data for 1 hour.
                'ttl' => 60*60*1
            ],
            'plugins' => [
                [
                    'name' => 'serializer',
                    'options' => [
                    ],
                ],
            ],
        ],
    ],
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => Driver::class,
                'params'        => [
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => '0000',
                    'dbname'   => 'api',
                    'driverOptions' => [
                        1002 => 'SET NAMES utf8',
                    ],
                ],
            ],
        ],
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AttributeDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ],
            ],
        ],
    ],
];