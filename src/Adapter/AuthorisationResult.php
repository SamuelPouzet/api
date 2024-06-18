<?php

namespace SamuelPouzet\Api\Adapter;


use Laminas\Http\Response;

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
    public const AUTHORIZED = Response::STATUS_CODE_200;
    /**
     *
     */
    public const INVALID_TOKEN = Response::STATUS_CODE_500;
    /**
     *
     */
    public const NOT_AUTHORIZED = Response::STATUS_CODE_403;
    /**
     *
     */
    public const MISSING_CONFIG = Response::STATUS_CODE_500;
    /**
     *
     */
    public const NOT_ACTIVATED = Response::STATUS_CODE_403;
    /**
     *
     */
    public const NEEDS_CONNEXION = Response::STATUS_CODE_401;

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