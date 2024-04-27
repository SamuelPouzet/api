<?php

namespace SamuelPouzet\Api\Adapter;


use SamuelPouzet\Api\Interface\UserInterface;

class Result
{
    /**
     * Failure due to configuration mistake
     */
    public const CONF_ERROR = 1;

    /**
     * Failure due to identity being ambiguous.
     */
    public const FAILURE_IDENTITY_AMBIGUOUS = 2;

    /**
     * Failure due to user not found.
     */
    public const USER_NOT_FOUND = 3;

    /**
     * Allright, access granted
     */
    public const ACCESS_GRANTED = 200;

    /**
     * password rejected
     */
    public const PASSWORD_REJECTED = 5;

    /**
     * @var int
     */
    protected int $code;

    /**
     * @var ?UserInterface
     */
    protected ?UserInterface $user;

    /**
     * @var string
     */
    protected string $message;

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): Result
    {
        $this->code = $code;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): Result
    {
        $this->message = $message;
        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): Result
    {
        $this->user = $user;
        return $this;
    }


}