<?php

namespace SamuelPouzet\Api\Service;

use SamuelPouzet\Api\Adapter\AuthenticatedIdentity;
use SamuelPouzet\Api\Entity\AuthRefreshToken;
use SamuelPouzet\Api\Entity\User;
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
        protected AuthTokenService $tokenService,
        protected RoleService      $roleService,
        protected \DateInterval    $expirationDelay,
    )
    {
    }

    /**
     * @param array $credentials
     * @return IdentityInterface
     */
    public function createIdentity(User $credentials): IdentityInterface
    {
        $identity = (new AuthenticatedIdentity())
            ->setId($credentials->getId())
            ->setLogin($credentials->getLogin())
            ->setRoles($this->roleService->getRolesByList($credentials->getRoles()) ?? [])
            ->setBearerToken($this->tokenService->generateToken())
            ->setRefreshToken($this->tokenService->generateToken());

        $this->tokenService->saveAuthToken($identity, $credentials);
        $this->tokenService->saveRefreshToken($identity, $credentials, $this->expirationDelay);

        return $identity;
    }

    public function closeIdentity(AuthRefreshToken $token)
    {
        $this->tokenService->clearRefreshToken($token);

    }
}
