<?php

namespace SamuelPouzet\Api\Adapter;

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
    protected string $login;
    /**
     * @var array
     */
    protected array $roles;
    /**
     * @var string
     */
    protected string $bearerToken;
    /**
     * @var string
     */
    protected string $refreshToken;

    /**
     * @param array $data
     */
    public function __construct()
    {
    }

    /**
     * @return string
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

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return $this
     */
    public function setLogin(string $login): AuthenticatedIdentity
    {
        $this->login = $login;
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

    /**
     * @return string
     */
    public function getBearerToken(): string
    {
        return $this->bearerToken;
    }

    /**
     * @param string $bearerToken
     * @return $this
     */
    public function setBearerToken(string $bearerToken): AuthenticatedIdentity
    {
        $this->bearerToken = $bearerToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     * @return $this
     */
    public function setRefreshToken(string $refreshToken): AuthenticatedIdentity
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }
}
