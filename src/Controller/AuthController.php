<?php

namespace SamuelPouzet\Api\Controller;

use Laminas\Form\Form;
use Laminas\Http\Response;
use Laminas\View\Model\JsonModel;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Form\AuthForm;
use SamuelPouzet\Api\Service\AuthService;
use SamuelPouzet\Api\Service\AuthTokenService;
use SamuelPouzet\Api\Service\CookieService;
use SamuelPouzet\Api\Service\IdentityService;
use SamuelPouzet\Api\Service\JwtService;

class AuthController extends AbstractJsonController
{

    public function __construct(
        protected AuthService      $authService,
        protected IdentityService  $identityService,
        protected AuthTokenService $tokenService,
        protected CookieService    $cookieService,
        protected JwtService       $jwtService,
        protected string           $form,
    )
    {
    }

    public function postAction(): JsonModel
    {

        $postData = json_decode(file_get_contents("php://input"), true);
        $authForm = $this->getForm();

        $authForm->setData($postData);
        $message = '';
        if ($authForm->isValid()) {
            $data = $authForm->getData();

            $result = $this->authService->verify($data);

            if ($result->getCode() === Result::ACCESS_GRANTED) {
                $this->identityService->createIdentity($result->getUser());
                $identity = $this->identityService->getIdentity();

                if ($identity) {
                    $jwt = $this->jwtService
                        ->build()
                        ->addClaim('login', $identity->getUser()->getLogin())
                        ->addClaim('access_token', $this->tokenService->getAccessToken())
                        ->addClaim('access_token_expires_at', $this->tokenService->getAccessTokenExpiration()->format('Y-m-d H:i:s'))
                        ->addClaim('refresh_token', $this->tokenService->getRefreshToken())
                        ->setExpiration(new \DateInterval('P1Y'))
                        ->generate();
                    $this->cookieService
                        ->addCookie($this->response, 'authCookie', $jwt);
                    $this->authService->saveIdentity('purple-connexion', $identity);
                    $this->tokenService->saveRefreshToken($result->getUser(), new \DateInterval('P6M'), $this->tokenService->getRefreshToken());

                    return new JsonModel($identity->exportIdentity());
                }
                return new JsonModel([]);
            }
            $this->getResponse()->setStatusCode($result->getCode());
            $message = $result->getMessage();
        } else {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);
            $message = 'formerror';
        }


        return new JsonModel([
            'status' => $this->getResponse()->getStatusCode(),
            'message' => $message,
        ]);
    }

    protected function getForm(): Form
    {
        if (class_exists($this->form)) {
            return new $this->form();
        }
        throw new \Exception(sprintf('form %1$s does not exists', $this->form));
    }

}