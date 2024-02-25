<?php

namespace SamuelPouzet\Api\Controller;

use Laminas\Form\FormInterface;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use SamuelPouzet\Api\Adapter\AuthenticatedIdentity;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Form\AuthForm;
use SamuelPouzet\Api\Service\AuthService;
use SamuelPouzet\Api\Service\AuthTokenService;
use SamuelPouzet\Api\Service\IdentityService;
use SamuelPouzet\Api\Service\JwtService;

class AuthController extends AbstractActionController
{

    public function __construct(
        protected AuthService $authService,
        protected IdentityService $identityService
    )
    { }

    public function postAction(): JsonModel
    {
        // $postData = $this->params()->fromPost();

        $postData = json_decode(file_get_contents("php://input"), true);
        $authForm = $this->getForm();

        $authForm->setData($postData);
        $message = '';
        if ($authForm->isValid()) {
            $data = $authForm->getData();

            $result = $this->authService->verify($data);

            if($result->getCode() === Result::ACCESS_GRANTED) {
                return new JsonModel($result->getIdentity()->getArrayCopy());
            }
            $this->getResponse()->setStatusCode($result->getCode());
            $message = $result->getMessage();
        }else{
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);
            $message = 'formerror';
        }


        return new JsonModel([
            'status' => $this->getResponse()->getStatusCode(),
            'message' => $message,
        ]);
    }


    protected function getForm()
    {
        return new AuthForm();
    }

}