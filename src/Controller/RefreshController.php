<?php

namespace SamuelPouzet\Api\Controller;

use Laminas\Http\Response;
use \SamuelPouzet\Api\Controller\AbstractJsonController;
use Laminas\View\Model\JsonModel;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Service\AuthService;
use SamuelPouzet\Api\Service\AuthTokenService;
use SamuelPouzet\Api\Service\CookieService;
use SamuelPouzet\Api\Service\IdentityService;
use SamuelPouzet\Api\Service\JwtService;
use SamuelPouzet\Api\Trait\ParseCookie;

class RefreshController extends AbstractJsonController
{
    use ParseCookie;

    public function __construct(
        protected AuthService $authService,
        protected IdentityService $identityService,
        protected AuthTokenService $tokenService,
        protected CookieService $cookieService,
        protected JwtService $jwtService,
    ) {
    }

    public function postAction(): JsonModel
    {

        $cookie = $this->getCookie($this->getRequest(), 'authCookie');

        if(! $cookie) {
            // @todo error code: misconfiguration
            $this->apiProblem(Response::STATUS_CODE_403, 'Cookie is missing');
        }
        $token = $this->jwtService->parse($cookie);
        $oldAccessToken = $token->claims()->get('access_token');
        $this->authService->clear($oldAccessToken);

        $token = $token->claims()->get('refresh_token');
        $result = $this->authService->refresh($token);

        if($result->getCode() === Result::ACCESS_GRANTED) {

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

        return new JsonModel([
            'status' => $this->getResponse()->getStatusCode(),
            'message' => $message,
        ]);
    }
}
