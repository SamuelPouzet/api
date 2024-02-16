<?php

namespace SamuelPouzet\Api\Service;

use Laminas\Crypt\Password\Bcrypt;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Manager\TokenManager;
use SamuelPouzet\Api\Manager\UserManager;

class AuthService
{
    public function __construct(
        protected IdentityService $identityService,
        protected TokenManager $tokenManager,
        protected UserManager $userManager,
        protected SessionService $sessionService
    ) {
    }

    public function verify(array $credentials): Result
    {
        $result = new Result();

        $challengeUser = $this->userManager->getByUser($credentials['login']);

        if (! $challengeUser) {
            return $result
                ->setMessage(sprintf('user not found : %1$s', $credentials['login']))
                ->setCode(Result::USER_NOT_FOUND);
        }
        $crypt = new Bcrypt();
        if (! $crypt->verify($credentials['password'], $challengeUser['password'])) {
            return $result
                ->setMessage(sprintf('password rejected: %1$s', $credentials['password']))
                ->setCode(Result::PASSWORD_REJECTED);
        }

        $identity = $this->identityService->createIdentity($challengeUser);
        $this->tokenManager->saveAccessToken($identity);
        $this->tokenManager->saveRefreshToken($identity);
        $this->sessionService->write('identity', $identity);

        return $result
            ->setMessage('Access granted')
            ->setCode(Result::ACCESS_GRANTED)
            ->setIdentity($identity)
            ;
    }
}
