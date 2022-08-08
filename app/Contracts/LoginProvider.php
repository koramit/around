<?php

namespace App\Contracts;

use Illuminate\Http\RedirectResponse;

interface LoginProvider
{
    public function __invoke(array $data, string $mode);

    public function redirect(string $mode): RedirectResponse;

    public function getId(): ?string;

    public function getName(): ?string;

    public function getEmail(): ?string;

    public function getAvatar(): ?string;

    public function getNickname(): ?string;

    public function getStatus(): ?string;
}
