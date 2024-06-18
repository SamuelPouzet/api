<?php

namespace SamuelPouzet\Api\Service;

use Laminas\Session\Container;

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
        unset($this->sessionContainer->$key);
    }
}