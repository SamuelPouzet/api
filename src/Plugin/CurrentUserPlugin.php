<?php

namespace SamuelPouzet\Api\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use SamuelPouzet\Api\Interface\UserInterface;
use SamuelPouzet\Api\Service\IdentityService;
use SamuelPouzet\Api\Service\UserService;

class CurrentUserPlugin extends AbstractPlugin
{
    protected UserInterface|null $user = null;

    public function __construct(
        protected IdentityService $identityService,
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
        $this->user = $this->identityService->getIdentity()->getUser();

        return $this->user;
    }

}