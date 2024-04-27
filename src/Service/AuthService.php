<?php

namespace SamuelPouzet\Api\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Http\Response;
use SamuelPouzet\Api\Adapter\AuthenticatedIdentity;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Entity\AuthRefreshToken;

class AuthService
{
    public function __construct(
        protected IdentityService $identityService,
        protected EntityManager   $entityManager,
        protected SessionService  $sessionService,
        protected string $userEntity
    )
    {
    }

    public function verify(array $credentials): Result
    {
        $result = new Result();
        try {
            $challengeUser = $this->entityManager->getRepository($this->userEntity)->findOneBy(['login' => $credentials['login']]);
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

            return $result
                ->setMessage('Access granted')
                ->setCode(Response::STATUS_CODE_200)
                ->setUser($challengeUser);
        } catch (\Exception $exception) {
            return $result
                ->setMessage(sprintf('Exception : %1$s', $exception->getMessage()))
                ->setCode(Response::STATUS_CODE_500);
        }

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

    public function saveIdentity(AuthenticatedIdentity $identity): void
    {
        $this->sessionService->write($identity->getAccessToken(), $identity);
    }
}
