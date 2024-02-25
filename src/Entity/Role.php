<?php

namespace SamuelPouzet\Api\Entity;

use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(readOnly: false)]
#[ORM\Table(name: 'role')]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    protected int $id;

    #[ORM\Column(name: 'name')]
    protected string $name;

    #[ORM\Column(name: 'code')]
    protected string $code;

    #[ORM\ManyToMany(targetEntity: Role::class, mappedBy: "roles")]
    protected PersistentCollection $users;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: "childRoles")]
    #[ORM\JoinTable(name: "role_hierarchy")]
    #[ORM\JoinColumn(name: "child_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "parent_id", referencedColumnName: "id")]
    private PersistentCollection $parentRoles;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: "childRoles")]
    #[ORM\JoinTable(name: "role_hierarchy")]
    #[ORM\JoinColumn(name: "parent_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "child_id", referencedColumnName: "id")]
    protected PersistentCollection $childRoles;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->parentRoles = new PersistentCollection();
        $this->childRoles = new PersistentCollection();
        $this->users = new PersistentCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Role
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Role
    {
        $this->name = $name;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): Role
    {
        $this->code = $code;
        return $this;
    }

    public function getParentRoles(): PersistentCollection
    {
        return $this->parentRoles;
    }

    public function setParentRoles(PersistentCollection $parentRoles): Role
    {
        $this->parentRoles = $parentRoles;
        return $this;
    }

    public function getChildRoles(): PersistentCollection
    {
        return $this->childRoles;
    }

    public function setChildRoles(PersistentCollection $childRoles): Role
    {
        $this->childRoles = $childRoles;
        return $this;
    }

}