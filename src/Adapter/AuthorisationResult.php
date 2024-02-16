<?php

namespace SamuelPouzet\Api\Adapter;

/**
 *
 */
class AuthorisationResult
{

    /**
     *
     */
    public const NOT_LOADED = 0;
    /**
     *
     */
    public const AUTHORIZED = 1;
    /**
     *
     */
    public const INVALID_TOKEN = 2;
    /**
     *
     */
    public const NOT_AUTHORIZED = 3;
    /**
     *
     */
    public const MISSING_CONFIG = 4;
    /**
     *
     */
    public const NOT_ACTIVATED = 5;
    /**
     *
     */
    public const NEEDS_CONNEXION = 6;

    /**
     * @var int
     */
    protected int $status = self::NOT_LOADED;

    /**
     * @var string
     */
    protected string $responseMessage = '';

    /**
     *
     */
    public function __construct()
    {

    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): AuthorisationResult
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getResponseMessage(): string
    {
        return $this->responseMessage;
    }

    /**
     * @param string $responseMessage
     * @return $this
     */
    public function setResponseMessage(string $responseMessage): AuthorisationResult
    {
        $this->responseMessage = $responseMessage;
        return $this;
    }

}