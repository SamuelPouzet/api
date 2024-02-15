<?php

namespace SamuelPouzet\Api\Service;

use Laminas\Router\RouteMatch;
use Laminas\Http\Header\Authorization;
use SamuelPouzet\Api\Adapter\AuthorisationResult;

class AuthorizationService
{
    public function __construct(
        protected array $config
    ) {
    }

    public function authorize(RouteMatch $routeMatch, ?Authorization $authorization): AuthorisationResult
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

        //@ todo check bearer and roles
        $result->setStatus(AuthorisationResult::AUTHORIZED);
        return $result;
    }
}