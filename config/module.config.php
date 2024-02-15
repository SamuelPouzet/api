<?php

namespace SamuelPouzet\Api;

use Application\Controller\IndexController;
use Laminas\Router\Http\Literal;
use SamuelPouzet\Api\Controller\AuthController;
use SamuelPouzet\Api\Controller\Factories\AuthControllerFactory;
use SamuelPouzet\Api\Listener\ApiListener;
use SamuelPouzet\Api\Listener\AuthorizationListener;
use SamuelPouzet\Api\Listener\Factory\ApiListenerFactory;
use SamuelPouzet\Api\Listener\Factory\AuthorizationListenerFactory;
use SamuelPouzet\Api\Service\AuthorizationService;
use SamuelPouzet\Api\Service\AuthService;
use SamuelPouzet\Api\Service\AuthTokenService;
use SamuelPouzet\Api\Service\Factory\AuthorizationServiceFactory;
use SamuelPouzet\Api\Service\Factory\AuthServiceFactory;
use SamuelPouzet\Api\Service\Factory\AuthTokenServiceFactory;
use SamuelPouzet\Api\Service\Factory\IdentityServiceFactory;
use SamuelPouzet\Api\Service\Factory\JwtServiceFactory;
use SamuelPouzet\Api\Service\IdentityService;
use SamuelPouzet\Api\Service\JwtService;

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
        ],
    ],
    'JWT' => [
        'bearer' => 'thissecretmustbeoverriden',
    ],
    'listeners' => [
        ApiListener::class,
        AuthorizationListener::class,
    ],
    'controllers' => [
        'factories' => [
            AuthController::class => AuthControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            ApiListener::class => ApiListenerFactory::class,
            AuthorizationListener::class => AuthorizationListenerFactory::class,
            AuthorizationService::class => AuthorizationServiceFactory::class,
            AuthService::class => AuthServiceFactory::class,
            AuthTokenService::class => AuthTokenServiceFactory::class,
            IdentityService::class => IdentityServiceFactory::class,
            JwtService::class => JwtServiceFactory::class,
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
                    'roles' => '*'
                ],
                'post' => [
                    'allowed' => false,
                ],
            ],
        ],
    ],
];