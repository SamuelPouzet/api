<?php

namespace SamuelPouzet\Api\Manager;

use Doctrine\ORM\EntityManager;
use SamuelPouzet\Api\Entity\AuthRefreshToken;

class RefreshTokenManager
{

    public function __construct(
        protected EntityManager $entityManager
    )
    {}

    public function create(array $data)
    {
        $refreshToken = new AuthRefreshToken();
        $refreshToken->setUser($data['user'])
            ->setRefreshToken($data['token'])
            ->setExpires($data['expiration'])
            ;
        $this->entityManager->persist($refreshToken);
        $this->entityManager->flush();
        return $refreshToken;

    }
}