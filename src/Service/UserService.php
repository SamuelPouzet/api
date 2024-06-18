<?php

namespace SamuelPouzet\Api\Service;

use Application\Entity\User;
use Doctrine\ORM\EntityManager;
use Laminas\Http\Request;
use Laminas\Stdlib\RequestInterface;
use SamuelPouzet\Api\Interface\UserInterface;
use SamuelPouzet\Api\Trait\ParseCookie;

class UserService
{
    use ParseCookie;

    public function __construct(
        protected JwtService $jwtService,
        protected SessionService $sessionService,
        protected EntityManager $entityManager,
    )
    {

    }

    public function getCurrentUser(RequestInterface $request): UserInterface|null
    {
        $cookie = $this->getCookie($request, 'authCookie');

        // @todo response au lieu de null pour traitement ultérieur
        if(! $cookie) {
            return null;
        }
        $token = $this->jwtService->parse($cookie);
        if(! $token) {
            return null;
        }
        $claim = $token->claims()->get('access_token');
        if(! $claim) {
            return null;
        }

        $identity = $this->sessionService->read('purple-connexion');

        if(! $identity) {
            return null;
        }
        $identityUser = $identity['login'] ?? null;
        if(! $identityUser) {
            return null;
        }
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['login' => $identityUser]);
        if(! $user) {
            return null;
        }
        return $user;
    }

}