<?php

namespace SamuelPouzet\Api\Controller;

use \SamuelPouzet\Api\Controller\AbstractJsonController;
use Laminas\View\Model\JsonModel;

class ErrorController extends AbstractJsonController
{
    public function __construct()
    {

    }

    public function errorAction(): JsonModel
    {
        $statusCode = $this->params()->fromRoute("statusCode", 500);
        $errorMessage = $this->params()->fromRoute("message", '');
        $this->getResponse()->setStatusCode($statusCode);

        return new JsonModel([
            'status' => $statusCode,
            'message' => $errorMessage,
        ]);
    }
}