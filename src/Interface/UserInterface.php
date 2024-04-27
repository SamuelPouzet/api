<?php

namespace SamuelPouzet\Api\Interface;

interface UserInterface
{
    public function getId(): int;
    public function getLogin(): string;
    public function getMail(): string;
    public function getPassword(): string;
}