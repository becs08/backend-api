<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OffreController;
use App\Http\Controllers\Api\DemandeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Offres publiques
Route::get('/offres', [OffreController::class, 'index']);
Route::get('/offres/{offre}', [OffreController::class, 'show']);
Route::get('/categories', [OffreController::class, 'categories']);

// Routes protégées par Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::get('/dashboard/stats', [AuthController::class, 'dashboardStats']);

    // Offres
    Route::post('/offres', [OffreController::class, 'store']);
    Route::put('/offres/{offre}', [OffreController::class, 'update']);
    Route::delete('/offres/{offre}', [OffreController::class, 'destroy']);
    Route::get('/mes-offres', [OffreController::class, 'mesOffres']);

    // Demandes
    Route::post('/offres/{offre}/demandes', [DemandeController::class, 'store']);
    Route::put('/demandes/{demande}', [DemandeController::class, 'update']);
    Route::get('/mes-demandes', [DemandeController::class, 'mesDemandes']);
    Route::get('/demandes-recues', [DemandeController::class, 'demandesRecues']);
});
