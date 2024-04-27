<?php

namespace SamuelPouzet\Api\Controller;

use \SamuelPouzet\Api\Controller\AbstractJsonController;
use Laminas\View\Model\JsonModel;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Service\AuthService;

class RefreshController extends AbstractJsonController
{
    public function __construct(
        protected AuthService $authService,
    ) {
    }

    public function postAction(): JsonModel
    {
        $postData = json_decode(file_get_contents("php://input"), true);

        $result = $this->authService->refresh($postData);

        if($result->getCode() === Result::ACCESS_GRANTED) {
            return new JsonModel($result->getIdentity()->getArrayCopy());
        }
        $this->getResponse()->setStatusCode($result->getCode());
        $message = $result->getMessage();

        return new JsonModel([
            'status' => $this->getResponse()->getStatusCode(),
            'message' => $message,
        ]);
    }
}
