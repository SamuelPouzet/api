<?php

namespace SamuelPouzet\Api\Service;

use Doctrine\ORM\EntityManager;
use SamuelPouzet\Api\Adapter\AuthenticatedIdentity;
use SamuelPouzet\Api\Entity\AuthRefreshToken;
use SamuelPouzet\Api\Interface\IdentityInterface;
use SamuelPouzet\Api\Interface\UserInterface;

class AuthTokenService
{

    protected string $accessToken;
    protected string $refreshToken;
    protected \DateTimeImmutable $accessTokenExpiration;

    public function __construct(
        protected array $config,
        protected EntityManager $entityManager,
        protected JwtService $jwtService,
        protected CookieService $cookieService,
    )
    {
        $this->accessToken = $this->generateToken();
        $this->refreshToken = $this->generateToken();
        // @todo interval dans la config
        $this->accessTokenExpiration = (new \DateTimeImmutable() )->add(new \DateInterval('P1M') );
    }

    protected function generateToken(): string
    {
        return bin2hex(random_bytes($this->config['length']));
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getAccessTokenExpiration(): \DateTimeImmutable
    {
        return $this->accessTokenExpiration;
    }

    public function generateTokens(IdentityInterface $identity): JwtService
    {

        return
            $this->jwtService
                ->build()
                ->addClaim('login', $identity->getUser()->getLogin())
                ->addClaim('access_token', $this->accessToken)
                ->addClaim('access_token_expires_at', $this->accessTokenExpiration->format('Y-m-d H:i:s'))
                ->addClaim('refresh_token', $this->refreshToken)
                ->setExpiration(new \DateInterval('P1Y'))
            ;
    }


    public function saveRefreshToken(UserInterface $user, \DateInterval $dateInterval, string $token)
    {
        // @todo déplacer dans le Manager ad hoc
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
        // @todo déplacer dans le Manager
        $now = new \DateTime();
        $token->setExpires($now);
        $this->entityManager->persist($token);
        $this->entityManager->flush();
    }
}