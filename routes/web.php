<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class , 'index'])->name('dashboard');

    Route::get('/profile/complete', [\App\Http\Controllers\ProfileController::class , 'edit'])->name('profile.complete');
    Route::post('/profile/complete', [\App\Http\Controllers\ProfileController::class , 'update'])->name('profile.update');

    // Demandes de logement
    Route::get('/demande-logement/nouvelle', [\App\Http\Controllers\DemandeLogementController::class , 'create'])->name('demandes.create');
    Route::post('/demande-logement/nouvelle', [\App\Http\Controllers\DemandeLogementController::class , 'store'])->name('demandes.store');
});

Route::post('/login', [AuthController::class , 'login'])->name('login.post');
Route::post('/register', [AuthController::class , 'register'])->name('register.post');
Route::post('/logout', [AuthController::class , 'logout'])->name('logout');