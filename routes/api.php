<?php

use App\Http\Controllers\SchoolClassController;
use Illuminate\Support\Facades\Route;

Route::get('/schools/{schoolId}/classes', [SchoolClassController::class, 'classesBySchool']);
