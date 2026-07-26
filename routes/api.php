<?php

use App\Http\Controllers\SchoolClassController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/schools/{schoolId}/classes', [SchoolClassController::class, 'classesBySchool']);
});
