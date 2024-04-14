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
        protected RoleService      $roleService
    )
    {
    }

    /**
     * @param array $credentials
     * @return IdentityInterface
     */
    public function createIdentity(User $credentials): IdentityInterface
    {
        // @todo gestion des permissions
        $identity = (new AuthenticatedIdentity())
            ->setId($credentials->getId())
            ->setUser($credentials)
            ->setRoles($this->roleService->getRolesByList($credentials->getRoles()) ?? [])
            ;

        return $identity;
    }

    public function closeIdentity(AuthRefreshToken $token)
    {
        $this->tokenService->clearRefreshToken($token);

    }
}
