<?php

namespace App\Contracts;

interface AuthenticationAPI
{
    public function authenticate(string $login, string $password): array;

    public function getUserById(int $id): array;

    public function checkUserById(int $orgId): array;
}
