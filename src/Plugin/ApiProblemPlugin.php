<?php

namespace SamuelPouzet\Api\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\View\Model\JsonModel;

class ApiProblemPlugin extends AbstractPlugin
{
    public function __invoke(int $statusCode, string $message)
    {
        $this->getController()->getResponse()->setStatusCode($statusCode);
        return new JsonModel([
            'status' => $statusCode,
            'message' => $message
        ]);
    }
}