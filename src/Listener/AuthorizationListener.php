<?php

namespace SamuelPouzet\Api\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;
use SamuelPouzet\Api\Adapter\AuthorisationResult;
use SamuelPouzet\Api\Exception\NotAuthorizedException;
use SamuelPouzet\Api\Service\AuthorizationService;

class AuthorizationListener
{
    protected array $listeners;

    public function __construct(
        protected AuthorizationService $authorizationService
    ) {
    }

    public function attach(EventManagerInterface $events, $priority = 1): void
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH,
            [$this, 'authorize']
        );
    }

    public function authorize(MvcEvent $event): void
    {
        $auth = $this->authorizationService->authorize(
            $event->getRouteMatch(),
            $event->getRequest()->getHeaders()->get('Authorization')
        );

        if (AuthorisationResult::AUTHORIZED !== $auth->getStatus()) {
            throw new NotAuthorizedException($auth->getResponseMessage());
        }

    }
}
