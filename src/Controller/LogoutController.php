<?php

namespace SamuelPouzet\Api\Controller;

use \SamuelPouzet\Api\Controller\AbstractJsonController;
use Laminas\View\Model\JsonModel;
use SamuelPouzet\Api\Service\AuthService;
use SamuelPouzet\Api\Service\CookieService;
use SamuelPouzet\Api\Service\JwtService;
use SamuelPouzet\Api\Service\SessionService;
use SamuelPouzet\Api\Trait\ParseCookie;

class LogoutController extends AbstractJsonController
{
    use ParseCookie;

    public function __construct(
        protected AuthService $authService,
        protected CookieService $cookieService,
        protected JwtService $jwtService,
        protected SessionService $sessionService
    ) {
    }

    public function postAction(): JsonModel
    {
        $authCookie=$this->getCookie($this->getRequest(), 'authCookie');
        // @todo verifier que la connexion existe
        $token = $this->jwtService->parse($authCookie);
        $accessToken = $token->claims()->get('access_token');
        // on supprime la session
        $this->sessionService->remove($accessToken);
        // @todo faire expirer le refresh token

        //on supprime le cookie JWT
        $this->cookieService->addCookie($this->getResponse(), 'authCookie','');
        return new JsonModel([
            'status' => $this->getResponse()->getStatusCode(),
            'message' => 'logout complete'
        ]);
    }
}
