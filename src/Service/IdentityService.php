<?php

namespace SamuelPouzet\Api\Service;

use SamuelPouzet\Api\Adapter\AuthenticatedIdentity;
use SamuelPouzet\Api\Entity\AuthRefreshToken;
use SamuelPouzet\Api\Interface\IdentityInterface;
use SamuelPouzet\Api\Interface\UserInterface;

/**
 *
 */
class IdentityService
{
    protected ?IdentityInterface $identity = null;

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
    public function createIdentity(UserInterface $credentials): void
    {
        // @todo gestion des permissions
        $this->identity = (new AuthenticatedIdentity())
            ->setId($credentials->getId())
            ->setUser($credentials)
            ->setRoles($this->roleService->getRolesByList($credentials->getRoles()) ?? []);

        die(var_dump($this->identity));
    }

    public function getIdentity(): ?IdentityInterface
    {
        return $this->identity;
    }

    public function closeIdentity(AuthRefreshToken $token)
    {
        $this->identity = new AuthenticatedIdentity();
        $this->tokenService->clearRefreshToken($token);

    }
}
