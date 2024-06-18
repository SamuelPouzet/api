<?php

namespace SamuelPouzet\Api\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Http\Headers;
use Laminas\Mvc\MvcEvent;
use Laminas\Router\RouteMatch;
use SamuelPouzet\Api\Controller\ErrorController;
use SamuelPouzet\Api\Exception\MethodNotFoundException;

class RouteListener
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
            [$this, 'route'],
            $priority
        );
    }

    public function route(MvcEvent $event)
    {
        $routeMatch = $event->getRouteMatch();
        $method = $this->getAction($event);

        $controller = $routeMatch->getParam('controller');
        if (
            ! class_exists($controller) ||
            ! method_exists($controller, $method . 'Action')
        ) {
            $routeMatch->setParam('controller', ErrorController::class);
            $routeMatch->setParam('action', 'error');
            $routeMatch->setParam('statusCode', 404);
            $routeMatch->setParam('message', sprintf('Method %1$s doesn\'exists in class %2$s', $method, $controller));
            return;
        }
        $routeMatch->setParam('action', $method);
    }

    protected function getAction(MvcEvent $event): string
    {
        $request = $event->getApplication()->getRequest();
        $method = strtolower($request->getMethod());
        if(strtolower($method) === 'get') {
            if (null === $event->getRouteMatch()->getParam('id')) {
                $method = 'getAll';
            }
        }

        return $method;
    }
}
