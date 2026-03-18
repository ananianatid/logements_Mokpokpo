<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;

// ─── Public (no auth required) ────────────────────────────────────────────
Route::post('/student/login', [StudentController::class, 'login']);

// Reference data (public, used in sign-up flow)
Route::get('/student/residences', [StudentController::class, 'residences']);
Route::get('/student/reference',  [StudentController::class, 'referenceData']);

// ─── Protected student routes (Sanctum token required) ────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/student/logout', [StudentController::class, 'logout']);

    // Dashboard (full summary in one call)
    Route::get('/student/dashboard', [StudentController::class, 'dashboard']);

    // Profile
    Route::get('/student/profile',   [StudentController::class, 'profile']);
    Route::put('/student/profile',   [StudentController::class, 'updateProfile']);

    // Housing applications
    Route::get('/student/demandes',  [StudentController::class, 'demandes']);
    Route::post('/student/demandes', [StudentController::class, 'storeDemande']);

    // Contract
    Route::get('/student/contrat',   [StudentController::class, 'contrat']);

    // Incidents
    Route::post('/student/incidents', [StudentController::class, 'storeIncident']);
});