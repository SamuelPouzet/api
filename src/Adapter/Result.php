<?php

namespace SamuelPouzet\Api\Adapter;

use SamuelPouzet\Api\Interface\IdentityInterface;

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
    public const ACCESS_GRANTED = 4;

    /**
     * password rejected
     */
    public const PASSWORD_REJECTED = 5;

    /**
     * @var int
     */
    protected int $code;

    /**
     * @var ?IdentityInterface
     */
    protected ?IdentityInterface $identity;

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

    public function getIdentity(): ?IdentityInterface
    {
        return $this->identity;
    }

    public function setIdentity(IdentityInterface $identity): Result
    {
        $this->identity = $identity;
        return $this;
    }

}