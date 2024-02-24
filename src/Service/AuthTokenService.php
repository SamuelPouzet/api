<?php

namespace SamuelPouzet\Api\Service;

use Doctrine\ORM\EntityManager;
use SamuelPouzet\Api\Adapter\AuthenticatedIdentity;
use SamuelPouzet\Api\Entity\AuthAccessToken;
use SamuelPouzet\Api\Entity\AuthRefreshToken;
use SamuelPouzet\Api\Entity\User;

class AuthTokenService
{

    public function __construct(
        protected array $config,
        protected EntityManager $entityManager
    )
    {
    }

    public function generateToken()
    {
        return bin2hex(random_bytes($this->config['length']));
    }

    public function saveAuthToken(AuthenticatedIdentity $identity, User $user)
    {
        $entity = new AuthAccessToken();
        $entity->setUserId($identity->getId());
        $entity->setUser($user);
        $entity->setAccessToken($identity->getBearerToken());
        $entity->setDate(new \DateTime());
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function saveRefreshToken(AuthenticatedIdentity $identity, User $user)
    {
        $entity = new AuthRefreshToken();
        $entity->setUserId($identity->getId());
        $entity->setUser($user);
        $entity->setRefreshToken($identity->getRefreshToken());
        $entity->setDate(new \DateTime());
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}