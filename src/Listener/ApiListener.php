<?php

namespace SamuelPouzet\Api\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;
use SamuelPouzet\Api\Exception\MethodNotFoundException;

class ApiListener
{

    protected array $listeners;

    public function __construct()
    {
        // @todo récupérer données nécessaires
    }

    public function attach(EventManagerInterface $events, $priority = 1): void
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this, 'api']
        );
    }

    public function api(MvcEvent $event)
    {
        $request = $event->getApplication()->getRequest();
        $method = strtolower($request->getMethod());
        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getParam('controller');

        if (!method_exists($controller, $method . 'Action')) {
            throw new MethodNotFoundException(sprintf('Method %1$s doesn\'exists in class %1$s', $method, $controller));
        }
        $routeMatch->setParam('action', $method);
    }
}