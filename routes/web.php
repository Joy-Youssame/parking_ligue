<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AdminController; // ic j'importe le contrôleur Admin
use Illuminate\Support\Facades\Route;

// 1. Page d'accueil
Route::get('/', function () {
    return view('welcome');
});


// 2. Route Dashboard utilisateur (appelle la fonction index du ReservationController)
Route::get('/dashboard', [ReservationController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 3. Groupe de routes protégées par connexion (AUTH)
Route::middleware('auth')->group(function () {
    
    // --- Gestion du profil (Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Nos routes pour le Parking ---
    Route::post('/reserver', [ReservationController::class, 'reserver'])->name('reservation.demander');
    Route::post('/liberer/{id}', [ReservationController::class, 'liberer'])->name('reservation.liberer');

    // --- Espace Administrateur (préfixé par /admin) ---
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/valider/{id}', [AdminController::class, 'validerUser'])->name('admin.valider');
    });
});

require __DIR__.'/auth.php';