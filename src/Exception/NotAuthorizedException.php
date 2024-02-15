<?php

namespace SamuelPouzet\Api\Exception;

use Laminas\Http\Response;

class NotAuthorizedException extends \Exception
{
    protected $code = Response::STATUS_CODE_403;
}