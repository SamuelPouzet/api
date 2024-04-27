<?php

namespace SamuelPouzet\Api\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use SamuelPouzet\Api\Interface\UserInterface;

abstract class AbstractJsonController extends AbstractActionController
{
    protected function apiProblem(int $code, string $errormessage): JsonModel
    {
        $this->getResponse()->setStatusCode($code);
        return new JsonModel([
                'error' => $code,
                'message' => $errormessage,
            ]
        );
    }

    protected function currentUser(): UserInterface|null
    {
        return $this->myUser();
    }
}