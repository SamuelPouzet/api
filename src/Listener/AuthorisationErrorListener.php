<?php

namespace SamuelPouzet\Api\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Http\Response;
use Laminas\Mvc\MvcEvent;

class AuthorisationErrorListener
{

    protected array $listeners;

    public function attach(EventManagerInterface $events, $priority = 1): void
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH_ERROR,
            [$this, 'error']
        );
    }

    public function error(MvcEvent $event)
    {
        $exception = $event->getError();
        $response = $event->getResponse();
        $response->setStatusCode(Response::STATUS_CODE_404);
        //$response->setContent(json_encode($exception));
        $event->stopPropagation();
    }
}