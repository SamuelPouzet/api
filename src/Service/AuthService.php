<?php

namespace SamuelPouzet\Api\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Http\Response;
use Lcobucci\JWT\Token\Plain;
use SamuelPouzet\Api\Adapter\AuthenticatedIdentity;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Entity\AuthRefreshToken;
use SamuelPouzet\Api\Interface\AuthServiceInterface;
use SamuelPouzet\Api\Interface\IdentityInterface;
use SamuelPouzet\Api\Interface\UserInterface;

class AuthService implements AuthServiceInterface
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
            $challengeUser = $this->getUser($credentials);
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

    public function clear(string $oldAccessToken): void
    {
        $this->sessionService->remove($oldAccessToken);
    }

    public function refresh(string $token): Result
    {
        $result = new Result();

        $refreshToken = $this->entityManager->getRepository(AuthRefreshToken::class)->findOneBy([
            'refreshToken' => $token,
        ]);


        if (! $refreshToken) {
            return $result
                ->setMessage(sprintf('invalid token : %1$s', $token))
                ->setCode(Response::STATUS_CODE_403);
        }

        $now = new \DateTimeImmutable();
        if ($refreshToken->getExpires() <= $now) {
            return $result
                ->setMessage(sprintf('token expired: %1$s', $token))
                ->setCode(Response::STATUS_CODE_403);
        }

        $this->identityService->closeIdentity($refreshToken);

        return $result
            ->setMessage('Access granted')
            ->setCode(Response::STATUS_CODE_200)
            ->setUser($refreshToken->getUser());

    }

    protected function getUser(array $credentials): ?UserInterface
    {
        return $this->entityManager->getRepository($this->userEntity)->findOneBy(['login' => $credentials['login']]);
    }

    public function saveIdentity(string $name, IdentityInterface $identity): void
    {
        $this->sessionService->write($name, $identity->exportIdentity());
    }
}
