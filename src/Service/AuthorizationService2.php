<?php

namespace SamuelPouzet\Api\Service;

use Laminas\Mvc\MvcEvent;
use SamuelPouzet\Api\Adapter\AuthorisationResult;
use SamuelPouzet\Api\Trait\ParseCookie;

class AuthorizationService2
{
    use ParseCookie;

    public function __construct(
        protected IdentityService  $identityService,
        protected SessionService   $sessionService,
        protected RoleService      $roleService,
        protected JwtService       $jwtService,
        protected array            $config,
    )
    {
    }

    public function authorize(MvcEvent $event): AuthorisationResult
    {

        $result = new AuthorisationResult();
        $routeMatch = $event->getRouteMatch();

        // première chose, on regarde si on a un utilisateur connecté
        $currentUser = $this->currentUser();




        $allowedByDefault = (bool)$this->config['allowedByDefault'] ?? false;
        $controller = (string)$routeMatch->getParam('controller');
        $config = $this->config['controllers'][$controller] ?? null;
        //config not found
        if (count($config) === 0) {
            if ($allowedByDefault) {
                // no config provided but allowed by default
                $result->setStatus(AuthorisationResult::AUTHORIZED);
                return $result;
            }
            $result->setStatus(AuthorisationResult::MISSING_CONFIG);
            $result->setResponseMessage('No controller config provided and disallowed by default');
            return $result;
        }
        $method = strtolower((string)$routeMatch->getParam('action'));
        $config = $config[$method] ?? null;
        //config not found
        if (null === $config) {
            if ($allowedByDefault) {
                // no config provided but allowed by default
                $result->setStatus(AuthorisationResult::AUTHORIZED);
                return $result;
            }
            $result->setStatus(AuthorisationResult::MISSING_CONFIG);
            $result->setResponseMessage('No method config provided and disallowed by default ' . $method);
            return $result;
        }

        if (!isset($config['allowed'])) {
            $result->setStatus(AuthorisationResult::MISSING_CONFIG);
            $result->setResponseMessage('No allowed config provided stay sure to give information in configuration');
            return $result;
        }

        if (!$config['allowed']) {
            $result->setStatus(AuthorisationResult::NOT_ACTIVATED);
            $result->setResponseMessage('This method is not allowed for this controller');
            return $result;
        }

        if ($config['roles'] === '*') {
            // allowed for everybody
            $result->setStatus(AuthorisationResult::AUTHORIZED);
            return $result;
        }

        // @todo utiliser le userService
        $authCookie=$this->getCookie($event->getRequest(), 'authCookie');

        if (! $authCookie ) {
            // token not provided
            $result->setStatus(AuthorisationResult::INVALID_TOKEN);
            return $result;
        }

        // @todo verify token
        $token = $this->jwtService->parse($authCookie);

        $identity = $this->sessionService->read($token->claims()->get('access_token'));

        if (!$identity) {
            // no user connected, needs a connexion
            $result->setStatus(AuthorisationResult::NEEDS_CONNEXION);
            $result->setResponseMessage('Needs connexion');
            return $result;
        }

        if ($config['roles'] !== '@') {
            if ($this->roleService->isRoleGranted((array)$config['roles'], $identity)) {
                $result->setStatus(AuthorisationResult::AUTHORIZED);
                return $result;
            }
            // user connected but not allowed
            $result->setStatus(AuthorisationResult::NOT_AUTHORIZED);
            $result->setResponseMessage('Invalid credentials');
            return $result;
        }
        // only needs an authenticated user
        $result->setStatus(AuthorisationResult::AUTHORIZED);
        return $result;
    }

    protected function currentUser()
    {

    }

}