<?php

namespace SamuelPouzet\Api\Service;

use Laminas\Session\Container;
use Laminas\Session\SessionManager;

#[\AllowDynamicProperties]
class SessionService
{
    public function __construct(
        protected Container $sessionContainer
    )
    {
    }


    public function write(string $key, mixed $value): self
    {
        $this->sessionContainer->$key = $value;

        return $this;
    }

    public function read(string $key): mixed
    {
        return $this->sessionContainer->$key ?? null;
    }

    public function remove(string $key): void
    {
        $this->sessionContainer->getManager()->getStorage()->clear($key);
    }
}