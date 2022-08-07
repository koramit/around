<?php

namespace App\Contracts;

use Illuminate\Http\RedirectResponse;

interface LoginProvider
{
    public static function redirect(string $mode): RedirectResponse;

    public static function getConfigs();

    public function __construct(array $data, string $mode);

    public function getId(): ?string;

    public function getName(): ?string;

    public function getEmail(): ?string;

    public function getAvatar(): ?string;

    public function getNickname(): ?string;

    public function getStatus(): ?string;
}
