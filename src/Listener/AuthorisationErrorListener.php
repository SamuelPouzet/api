<?php

namespace SamuelPouzet\Api\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Http\Response;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\JsonModel;

class AuthorisationErrorListener
{

    protected array $listeners;

    public function attach(EventManagerInterface $events, int $priority = -10): void
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH_ERROR,
            [$this, 'error'],
            $priority
        );
    }

    public function error(MvcEvent $event)
    {
        $exception = $event->getParam('exception');

        $content = new JsonModel([
            'status' => $event->getResponse()->getStatusCode(),
            'message' => $exception?$exception->getMessage():$event->getError()
        ]);
        $event->setViewModel($content);
        $event->stopPropagation();
    }
}