<?php

namespace SamuelPouzet\Api\Service;

use SamuelPouzet\Api\Adapter\AuthenticatedIdentity;
use SamuelPouzet\Api\Interface\IdentityInterface;

/**
 *
 */
class IdentityService
{
    /**
     * @param AuthTokenService $tokenService
     */
    public function __construct(
        protected AuthTokenService $tokenService
    )
    {}

    /**
     * @param array $credentials
     * @return IdentityInterface
     */
    public function createIdentity(array $credentials): IdentityInterface
    {
        $identity = new AuthenticatedIdentity();
        $identity->setLogin($credentials['login']);
        $identity->setRole($credentials['role']??'');
        $identity->setBearerToken($this->tokenService->generateToken());
        $identity->setRefreshToken($this->tokenService->generateToken());

        return $identity;
    }

}