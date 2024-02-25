<?php

namespace SamuelPouzet\Api\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'auth_access_token')]
class AuthAccessToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    protected int $id;

    #[ORM\Column(name: "user_id")]
    protected string $userId;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    protected User $user;

    #[ORM\Column(name: "access_token")]
    protected string $accessToken;

    #[ORM\Column(name: "date")]
    protected \DateTime $date;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): AuthAccessToken
    {
        $this->id = $id;
        return $this;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): AuthAccessToken
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): AuthAccessToken
    {
        $this->user = $user;
        return $this;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): AuthAccessToken
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): AuthAccessToken
    {
        $this->date = $date;
        return $this;
    }
}