<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\OutilsTopoController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

// Page d'accueil publique
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Routes protegees par authentification
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dossiers CRUD
    Route::resource('dossiers', DossierController::class);

    // Documents (imbriques dans dossiers)
    Route::post('dossiers/{dossier}/documents', [DocumentController::class, 'store'])
        ->name('documents.store');
    Route::delete('documents/{document}', [DocumentController::class, 'destroy'])
        ->name('documents.destroy');

    // ── Génération PDF (3 types) ──────────────────────────────────────
    Route::get('dossiers/{dossier}/pdf/pv',      [PdfController::class, 'pv'])->name('pdf.pv');
    Route::get('dossiers/{dossier}/pdf/rapport',  [PdfController::class, 'rapport'])->name('pdf.rapport');
    Route::get('dossiers/{dossier}/pdf/fiche',    [PdfController::class, 'fiche'])->name('pdf.fiche');
    // Ancienne route (compatibilité)
    Route::get('dossiers/{dossier}/pdf',          [PdfController::class, 'rapport'])->name('pdf.dossier');

    // Outils topographiques
    Route::get('outils', [OutilsTopoController::class, 'index'])->name('outils.index');
    Route::post('outils/distance', [OutilsTopoController::class, 'calculerDistance'])->name('outils.distance');
    Route::post('outils/surface', [OutilsTopoController::class, 'calculerSurface'])->name('outils.surface');
    Route::post('outils/convertir', [OutilsTopoController::class, 'convertirUnites'])->name('outils.convertir');
    Route::post('outils/tantiemes', [OutilsTopoController::class, 'calculerTantiemes'])->name('outils.tantiemes');

    // Chatbot
    Route::get('chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('chatbot/repondre', [ChatbotController::class, 'repondre'])->name('chatbot.repondre');

    // ── Administration (Accès réservé) ────────────────────────────────
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

        // Gestion des utilisateurs
        Route::get('/users',              [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit',  [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}',       [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',    [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Gestion globale des dossiers (tous les utilisateurs)
        Route::get('/dossiers',                      [\App\Http\Controllers\Admin\DossierAdminController::class, 'index'])->name('dossiers.index');
        Route::get('/dossiers/{dossier}',            [\App\Http\Controllers\Admin\DossierAdminController::class, 'show'])->name('dossiers.show');
        Route::get('/dossiers/{dossier}/edit',       [\App\Http\Controllers\Admin\DossierAdminController::class, 'edit'])->name('dossiers.edit');
        Route::put('/dossiers/{dossier}',            [\App\Http\Controllers\Admin\DossierAdminController::class, 'update'])->name('dossiers.update');
        Route::delete('/dossiers/{dossier}',         [\App\Http\Controllers\Admin\DossierAdminController::class, 'destroy'])->name('dossiers.destroy');
    });
});

require __DIR__.'/auth.php';
