<?php

namespace SamuelPouzet\Api\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;
use SamuelPouzet\Api\Adapter\AuthorisationResult;
use SamuelPouzet\Api\Controller\ErrorController;
use SamuelPouzet\Api\Exception\MethodNotFoundException;
use SamuelPouzet\Api\Exception\NotAuthorizedException;
use SamuelPouzet\Api\Service\AuthorizationService;

class AuthorizationListener
{
    protected array $listeners;

    public function __construct(
        protected AuthorizationService $authorizationService
    )
    {
    }

    public function attach(EventManagerInterface $events, int $priority = 1): void
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this, 'authorize'],
            $priority
        );
    }

    public function authorize(MvcEvent $event): void
    {

        $auth = $this->authorizationService->authorize($event);
        $routeMatch = $event->getRouteMatch();
        if (AuthorisationResult::AUTHORIZED !== $auth->getStatus()) {
            $routeMatch->setParam('controller', ErrorController::class);
            $routeMatch->setParam('action', 'error');
            $routeMatch->setParam('statusCode', $auth->getStatus());
            $routeMatch->setParam('message', $auth->getResponseMessage());
            return;
        }
        try {
            $this->api($event);
        } catch (\Exception $exception) {
            // @todo
            die($exception->getMessage());
        }
    }

    protected function api(MvcEvent $event)
    {
        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $method = $routeMatch->getParam('action');

        if (!method_exists($controller, $method . 'Action')) {
            throw new MethodNotFoundException(sprintf('Method %1$s doesn\'exists in class %2$s', $method, $controller));
        }
        $routeMatch->setParam('action', $method);
    }
}
