<?php

namespace SamuelPouzet\Api\Service;

class AuthTokenService
{

    public function __construct(
        protected array          $config
    )
    {
    }

    public function generateToken()
    {
        return bin2hex(random_bytes($this->config['length']));
    }
}