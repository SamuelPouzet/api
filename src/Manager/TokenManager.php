<?php

namespace SamuelPouzet\Api\Manager;

use SamuelPouzet\Api\Adapter\AuthenticatedIdentity;

class TokenManager
{
    public const ACCESS = 'auth_access_token';
    public const REFRESH = 'auth_refresh_token';

    public function __construct(
        protected \PDO $connexion
    )
    {
    }

    public function saveAccessToken(AuthenticatedIdentity $identity): void
    {
        $sql = 'INSERT INTO `auth_access_token`( `user`, `bearer_token`, `date`) VALUES (:user, :token, CURRENT_TIMESTAMP)';
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue('user', $identity->getId());
        $stmt->bindValue('token', $identity->getBearerToken());
        $stmt->execute();
    }

    public function saveRefreshToken(AuthenticatedIdentity $identity): void
    {
        $sql = 'INSERT INTO `auth_refresh_token`( `user`, `refresh_token`, `date`) VALUES (:user, :token, CURRENT_TIMESTAMP)';
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue('user', $identity->getId());
        $stmt->bindValue('token', $identity->getBearerToken());
        $stmt->execute();
    }
}
