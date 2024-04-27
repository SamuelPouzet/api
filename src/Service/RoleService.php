<?php

namespace SamuelPouzet\Api\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\PersistentCollection;
use Laminas\Cache\Storage\Adapter\Filesystem;
use SamuelPouzet\Api\Adapter\AuthenticatedIdentity;
use SamuelPouzet\Api\Entity\Role;
use SamuelPouzet\Api\Interface\IdentityInterface;

class RoleService
{

    protected $roleList;

    public function __construct(
        protected Filesystem    $cache,
        protected EntityManager $entityManager
    )
    {
        $this->init();
    }

    public function isRoleGranted(array $roles, IdentityInterface $identity): bool
    {

        if ($this->roleList === null) {
            $this->init();
        }
        return count(array_intersect($identity->getRoles(), $roles)) > 0;
    }

    protected function init($force = false): void
    {
        if (isset($this->roleList)) {
            return;
        }

        if ($force) {
            $this->cache->removeItem('roleList');
        }

        if ($this->cache->getItem('roleList')) {
            //get data from cache
            $this->roleList = $this->cache->getItem('roleList');
            return;
        }

        $roleList = $this->entityManager->getRepository(Role::class)->findAll();

        foreach ($roleList as $role) {
            $roleName = $role->getCode();
            $this->getChildren($roleName, $role);
        }

        $this->cache->addItem('roleList', $this->roleList);
    }

    protected function getChildren(string $roleName, Role $role): void
    {
        $this->roleList[$roleName][] = $role->getCode();
        if ($children = $role->getChildRoles()) {
            foreach ($children as $child) {
                $this->getChildren($roleName, $child);
            }
        }
    }

    public function getRolesByList(PersistentCollection $roleList): array
    {
        if ($this->roleList === null) {
            $this->init();
        }
        $userRoles = [];
        foreach ($roleList as $role) {
            $userRoles = array_merge($userRoles, $this->roleList[$role->getCode()]);
        }
        return array_unique($userRoles);
    }

}