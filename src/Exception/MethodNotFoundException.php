<?php

namespace SamuelPouzet\Api\Exception;

use Laminas\Http\Response;

class MethodNotFoundException extends \Exception
{
    protected $code = Response::STATUS_CODE_500;

}