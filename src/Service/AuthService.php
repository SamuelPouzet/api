<?php

namespace SamuelPouzet\Api\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Http\Response;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Entity\AuthRefreshToken;
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

    public function refresh(array $postData): Result
    {

        $result = new Result();

        if (! isset($postData['token'])) {
            return $result
                ->setMessage(sprintf('token not found'))
                ->setCode(401);
        }

        $token = $postData['token'];
        $refreshToken = $this->entityManager->getRepository(AuthRefreshToken::class)->findOneBy([
            'refreshToken' => $token,
        ]);


        if (! $refreshToken) {
            return $result
                ->setMessage(sprintf('invalid token : %1$s', $token))
                ->setCode(401);
        }

        $now = new \DateTimeImmutable();
        if ($refreshToken->getExpires() <= $now) {
            return $result
                ->setMessage(sprintf('token expired: %1$s', $token))
                ->setCode(401);
        }

        $this->identityService->closeIdentity($refreshToken);
        $identity = $this->identityService->createIdentity($refreshToken->getUser());
        $this->sessionService->write($identity->getBearerToken(), $identity);

        return $result
            ->setMessage('Access granted')
            ->setCode(Response::STATUS_CODE_200)
            ->setIdentity($identity);

    }
}
