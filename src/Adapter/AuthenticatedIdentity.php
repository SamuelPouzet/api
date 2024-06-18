<?php

namespace SamuelPouzet\Api\Adapter;

use SamuelPouzet\Api\Interface\IdentityInterface;
use SamuelPouzet\Api\Interface\UserInterface;

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
     * @var UserInterface
     */
    protected UserInterface $user;
    /**
     * @var array
     */
    protected array $roles;


    /**
     * @var ?string
     */
    protected ?string $refresh_token;


    public function __construct()
    {
    }

    public function exportIdentity(): array
    {
        return [
            'id' => $this->getId(),
            'login' => $this->getUser()->getLogin(),
            'mail' => $this->getUser()->getMail(),
            'roles' => $this->getRoles()
        ];
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

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): AuthenticatedIdentity
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

}
