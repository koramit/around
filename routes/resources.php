<?php

use App\Http\Controllers\Resources\AdmissionController;
use App\Http\Controllers\Resources\CovidLabController;
use App\Http\Controllers\Resources\CovidVaccineController;
use App\Http\Controllers\Resources\PatientRecentlyAdmissionController;
use App\Http\Controllers\Resources\PeopleController;
use App\Http\Controllers\Resources\WardController;

Route::post('admissions', AdmissionController::class)
    ->name('admissions.show');
Route::post('patient-recently-admission', PatientRecentlyAdmissionController::class)
    ->name('patient-recently-admission.show');
Route::get('wards', WardController::class)
    ->name('wards');
Route::get('people', PeopleController::class)
    ->name('people');
Route::post('covid-lab', CovidLabController::class)
    ->name('covid-lab');
Route::post('covid-vaccine', CovidVaccineController::class)
    ->name('covid-vaccine');
