<?php

namespace SamuelPouzet\Api\Service;

use Laminas\Http\Header\SetCookie;
use Laminas\Http\Response;
use Laminas\Stdlib\ResponseInterface;

class CookieService
{

    public function __construct(
        protected Response $response
    )
    {

    }

    public function addCookie(ResponseInterface $response, string $name, string $value)
    {
        $cookie = new SetCookie(
            $name,
            $value,
            $expires = ((new \DateTime())->add(new \DateInterval('P1D')))->format('Y-m-d H:i:s'),
            null,
            null,
            false,
            false
        );

        $response->getHeaders()->addHeader($cookie);
    }

}