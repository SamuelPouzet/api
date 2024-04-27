<?php

namespace SamuelPouzet\Api\Service;

use Doctrine\ORM\EntityManager;
use SamuelPouzet\Api\Adapter\AuthenticatedIdentity;
use SamuelPouzet\Api\Entity\AuthRefreshToken;
use SamuelPouzet\Api\Interface\IdentityInterface;
use SamuelPouzet\Api\Interface\UserInterface;

class AuthTokenService
{

    public function __construct(
        protected array $config,
        protected EntityManager $entityManager,
        protected JwtService $jwtService,
        protected CookieService $cookieService,
    )
    {
    }

    protected function generateToken(): string
    {
        return bin2hex(random_bytes($this->config['length']));
    }

    public function generateTokens(IdentityInterface $identity): JwtService
    {
        $identity->setAccessToken($this->generateToken());
        $identity->setRefreshToken($this->generateToken());

        // @todo interval dans la config
        $expiration = (new \DateTime() )->add(new \DateInterval('P1M') );
        $identity->setAccessTokenExpiration($expiration);


        // @todo ajouter de la sécurité, IP, etc
        return
            $this->jwtService
                ->build()
                ->addClaim('login', $identity->getUser()->getLogin())
                ->addClaim('access_token', $identity->getAccessToken())
                ->addClaim('access_token_expires_at', $identity->getAccessTokenExpiration()->format('Y-m-d H:i:s'))
                ->addClaim('refresh_token', $identity->getRefreshToken())
                ->setExpiration(new \DateInterval('P1Y'))
            ;
    }


    public function saveRefreshToken(UserInterface $user, \DateInterval $dateInterval, string $token)
    {
        // @todo déplacer dans le manager ad hoc
        $entity = new AuthRefreshToken();
        $entity->setUserId($user->getId());
        $entity->setUser($user);
        $entity->setRefreshToken($token);
        $entity->setExpires((new \DateTime())->add($dateInterval));
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function clearRefreshToken(AuthRefreshToken $token)
    {
        // @todo déplacer dans le manager
        $now = new \DateTime();
        $token->setExpires($now);
        $this->entityManager->persist($token);
        $this->entityManager->flush();
    }
}