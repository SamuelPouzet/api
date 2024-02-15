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
    protected string $id;
    /**
     * @var string
     */
    protected string $login;
    /**
     * @var string
     */
    protected string $role;
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
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id): AuthenticatedIdentity
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

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return $this
     */
    public function setRole(string $role): AuthenticatedIdentity
    {
        $this->role = $role;
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
