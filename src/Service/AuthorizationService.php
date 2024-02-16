<?php

namespace SamuelPouzet\Api\Service;

use Laminas\Router\RouteMatch;
use Laminas\Http\Header\Authorization;
use SamuelPouzet\Api\Adapter\AuthorisationResult;

class AuthorizationService
{
    public function __construct(
        protected array $config,
        protected SessionService $sessionService
    ) {
    }

    public function authorize(RouteMatch $routeMatch, false|Authorization $authorization): AuthorisationResult
    {
        $result = new AuthorisationResult();

        $allowedByDefault = $this->config['allowedByDefault'] ?? false;

        $controller = $routeMatch->getParam('controller');
        $config = $this->config['controllers'][$controller] ?? null;
        //config not found
        if (null === $config) {
            if ($allowedByDefault) {
                // no config provided but allowed by default
                $result->setStatus(AuthorisationResult::AUTHORIZED);
                return $result;
            }
            $result->setStatus(AuthorisationResult::MISSING_CONFIG);
            $result->setResponseMessage('No controller config provided and disallowed by default');
            return $result;
        }
        $method = strtolower($routeMatch->getParam('action'));
        $config = $config[$method] ?? null;
        //config not found
        if (null === $config) {
            if ($allowedByDefault) {
                // no config provided but allowed by default
                $result->setStatus(AuthorisationResult::AUTHORIZED);
                return $result;
            }
            $result->setStatus(AuthorisationResult::MISSING_CONFIG);
            $result->setResponseMessage('No method config provided and disallowed by default');
            return $result;
        }

        if (! isset($config['allowed'])) {
            $result->setStatus(AuthorisationResult::MISSING_CONFIG);
            $result->setResponseMessage('No allowed config provided stay sure to give information in configuration');
            return $result;
        }

        if (! $config['allowed']) {
            $result->setStatus(AuthorisationResult::NOT_ACTIVATED);
            $result->setResponseMessage('This method is not allowed for this controller');
            return $result;
        }

        //@ todo check bearer and roles
        if ($config['roles'] === '*') {
            // allowed for everybody
            $result->setStatus(AuthorisationResult::AUTHORIZED);
            return $result;
        }

        $identity = $this->sessionService->read('identity');

        if(! $identity) {
            // no user connected, needs a connexion
            $result->setStatus(AuthorisationResult::NEEDS_CONNEXION);
            $result->setResponseMessage('Needs connexion');
            return $result;
        }

//        if ($config['roles'] === '@') {
//            // allowed for all connected users
//            $result->setStatus(AuthorisationResult::AUTHORIZED);
//            return $result;
//        }

        $result->setStatus(AuthorisationResult::AUTHORIZED);
        return $result;
    }
}