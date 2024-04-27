<?php

namespace SamuelPouzet\Api\Entity;

use Doctrine\ORM\Mapping as ORM;
use SamuelPouzet\Api\Interface\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'auth_refresh_token')]
class AuthRefreshToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    protected int $id;

    #[ORM\Column(name: "user_id")]
    protected string $userId;

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    protected UserInterface $user;

    #[ORM\Column(name: "refresh_token")]
    protected string $refreshToken;

    #[ORM\Column(name: "expires")]
    protected \DateTime $expires;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): AuthRefreshToken
    {
        $this->id = $id;
        return $this;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): AuthRefreshToken
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): AuthRefreshToken
    {
        $this->user = $user;
        return $this;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): AuthRefreshToken
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    public function getExpires(): \DateTime
    {
        return $this->expires;
    }

    public function setExpires(\DateTime $expires): AuthRefreshToken
    {
        $this->expires = $expires;
        return $this;
    }

}