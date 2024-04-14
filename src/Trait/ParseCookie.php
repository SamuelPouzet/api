<?php

namespace SamuelPouzet\Api\Trait;

use Laminas\Stdlib\RequestInterface;

trait ParseCookie
{
    private function getCookie(RequestInterface $request, string $name): string
    {
        $cookie = $request->getCookie();
        if ($cookie->offsetExists($name)) {
            return $cookie->offsetGet($name);
        }
        return '';
    }
}