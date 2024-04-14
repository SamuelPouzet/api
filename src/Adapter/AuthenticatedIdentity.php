<?php

namespace SamuelPouzet\Api\Adapter;

use SamuelPouzet\Api\Entity\User;
use SamuelPouzet\Api\Interface\IdentityInterface;

/**
 *
 */
class AuthenticatedIdentity implements IdentityInterface
{
    /**
     * @var string
     */
    protected int $id;
    /**
     * @var string
     */
    protected User $user;
    /**
     * @var array
     */
    protected array $roles;

    /**
     * @var ?string
     */
    protected ?string $access_token;

    /**
     * @var ?\DateTime
     */
    protected ?\DateTime $access_token_expiration;

    /**
     * @var ?string
     */
    protected ?string $refresh_token;


    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(int $id): AuthenticatedIdentity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): AuthenticatedIdentity
    {
        $this->user = $user;
        return $this;
    }



    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): AuthenticatedIdentity
    {
        $this->roles = $roles;
        return $this;
    }

    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    public function setAccessToken(string $access_token): AuthenticatedIdentity
    {
        $this->access_token = $access_token;
        return $this;
    }

    public function getAccessTokenExpiration(): \DateTime
    {
        return $this->access_token_expiration;
    }

    public function setAccessTokenExpiration(\DateTime $access_token_expiration): AuthenticatedIdentity
    {
        $this->access_token_expiration = $access_token_expiration;
        return $this;
    }

    public function getRefreshToken(): string
    {
        return $this->refresh_token;
    }

    public function setRefreshToken(string $refresh_token): AuthenticatedIdentity
    {
        $this->refresh_token = $refresh_token;
        return $this;
    }

}
