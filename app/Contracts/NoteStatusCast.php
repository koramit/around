<?php

namespace App\Contracts;

interface NoteStatusCast
{
    public function getStatuses(): array;

    public function getCode(string $label): int;
}
