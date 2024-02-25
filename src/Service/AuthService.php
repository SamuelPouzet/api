<?php

namespace SamuelPouzet\Api\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Http\Response;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Entity\User;
use SamuelPouzet\Api\Manager\TokenManager;

class AuthService
{
    public function __construct(
        protected IdentityService $identityService,
        protected EntityManager   $entityManager,
        protected SessionService  $sessionService
    )
    {
    }

    public function verify(array $credentials): Result
    {
        $result = new Result();
        $challengeUser = $this->entityManager->getRepository(User::class)->findOneBy(['login' => $credentials['login']]);
        if (!$challengeUser) {
            return $result
                ->setMessage(sprintf('user not found : %1$s', $credentials['login']))
                ->setCode(Response::STATUS_CODE_401);
        }
        $crypt = new Bcrypt();
        if (!$crypt->verify($credentials['password'], $challengeUser->getPassword())) {
            return $result
                ->setMessage(sprintf('password rejected: %1$s', $credentials['password']))
                ->setCode(Response::STATUS_CODE_401);
        }

        $identity = $this->identityService->createIdentity($challengeUser);
        $this->sessionService->write($identity->getBearerToken(), $identity);

        return $result
            ->setMessage('Access granted')
            ->setCode(Response::STATUS_CODE_200)
            ->setIdentity($identity);
    }
}
