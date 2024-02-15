<?php

namespace SamuelPouzet\Api\Service;

use Laminas\Crypt\Password\Bcrypt;
use SamuelPouzet\Api\Adapter\AuthenticatedIdentity;
use SamuelPouzet\Api\Adapter\Result;

class AuthService
{
    public function __construct(
        protected \PDO $connexion
    ) {
    }

    public function verify(array $credentials): Result
    {
        $result = new Result();

        $stmt = $this->connexion->prepare('select * from user where name = :login');
        $stmt->bindValue('login', $credentials['login']);
        $stmt->execute();
        $challengeUser = $stmt->fetch(\PDO::FETCH_ASSOC);
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

        return $result
            ->setMessage('Access granted')
            ->setCode(Result::ACCESS_GRANTED)
            ->setIdentity(new AuthenticatedIdentity($challengeUser))
            ;
    }
}
