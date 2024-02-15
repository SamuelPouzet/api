<?php

namespace SamuelPouzet\Api\Adapter;

use SamuelPouzet\Api\Interface\IdentityInterface;

class AuthenticatedIdentity implements IdentityInterface
{

    protected string $login;
    protected string $role;
    protected string $bearerToken;
    protected string $refreshToken;

    public function __construct(array $data = [])
    {
        if ($data) {
            $this->hydrate($data);
        }
    }

    protected function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst(strtolower($key));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): AuthenticatedIdentity
    {
        $this->login = $login;
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): AuthenticatedIdentity
    {
        $this->role = $role;
        return $this;
    }

    public function getBearerToken(): string
    {
        return $this->bearerToken;
    }

    public function setBearerToken(string $bearerToken): AuthenticatedIdentity
    {
        $this->bearerToken = $bearerToken;
        return $this;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): AuthenticatedIdentity
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }


}