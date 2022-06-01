<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class CaseRecordController extends Controller
{
    public function index()
    {
        return Inertia::render('Procedures/AcuteHemodialysisIndex');
    }
}
