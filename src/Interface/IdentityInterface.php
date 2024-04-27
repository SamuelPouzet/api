<?php

namespace SamuelPouzet\Api\Interface;

interface IdentityInterface
{
    public function getId(): int;
    public function getUser(): UserInterface;
    public function exportIdentity(): array;
}