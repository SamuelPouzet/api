<?php

namespace SamuelPouzet\Api\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;
use SamuelPouzet\Api\Controller\ErrorController;
use SamuelPouzet\Api\Exception\MethodNotFoundException;

class ApiListener
{

    protected array $listeners;

    public function __construct()
    {
        // @todo récupérer données nécessaires
    }

    public function attach(EventManagerInterface $events, int $priority = 1): void
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this, 'api'],
            $priority
        );
    }

    public function api(MvcEvent $event)
    {
        $request = $event->getApplication()->getRequest();
        $method = strtolower($request->getMethod());
        $routeMatch = $event->getRouteMatch();

        $controller = $routeMatch->getParam('controller');
        if (
            ! class_exists($controller) ||
            ! method_exists($controller, $method . 'Action')
        ) {
            $routeMatch->setParam('controller', ErrorController::class);
            $routeMatch->setParam('action', 'error');
            $routeMatch->setParam('statusCode', 404);
            $routeMatch->setParam('message', 'Method %1$s doesn\'exists in class %1$s', $method, $controller);
            return;
        }
        $routeMatch->setParam('action', $method);
    }

}