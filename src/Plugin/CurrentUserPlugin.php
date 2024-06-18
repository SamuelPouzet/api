<?php

namespace SamuelPouzet\Api\Plugin;

use Doctrine\ORM\EntityManager;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use SamuelPouzet\Api\Entity\User;
use SamuelPouzet\Api\Interface\UserInterface;
use SamuelPouzet\Api\Service\IdentityService;
use SamuelPouzet\Api\Service\UserService;

class CurrentUserPlugin extends AbstractPlugin
{
    protected UserInterface|null $user = null;

    public function __construct(
        protected IdentityService $identityService,
        protected EntityManager $entityManager,
        protected string $userEntity,
    )
    {

    }

    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    public function __invoke(): UserInterface|null
    {

        if ($this->user) {
            return $this->user;
        }
        $id = $this->identityService->getIdentity()->getId();
        $this->user = $this->entityManager->getRepository($this->userEntity)->find($id);
        return $this->user;
    }

}