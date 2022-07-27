<?php

namespace App\Contracts;

interface CovidInfoAPI
{
    public function checkCovidLab(int $hn): array;

    public function checkCovidVaccine(string $cid, $raw = false): array;
}
