<?php

namespace App\Contracts;

interface PatientAPI
{
    public function getPatient(string $hn): array;

    public function getAdmission(string $an): array;

    public function recentlyAdmission(string $hn): array;
}
