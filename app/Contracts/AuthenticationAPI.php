<?php

namespace App\Contracts;

interface AuthenticationAPI
{
    public function authenticate(string $login, string $password): array;

    public function getUserById(string $id): array;
}
