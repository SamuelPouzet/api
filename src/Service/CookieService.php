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
            ((new \DateTime())->add(new \DateInterval('P1Y')))->format('Y-m-d H:i:s'),
            null,
            null,
            false,
            false,
            null,
            null,
            'None'
        );

        $response->getHeaders()->addHeader($cookie);
    }



}