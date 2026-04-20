<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\OutilsTopoController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProfileController;
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

    // Generation PDF avec DomPDF
    Route::get('dossiers/{dossier}/pdf', function (\App\Models\Dossier $dossier) {
        if ($dossier->user_id !== auth()->id()) {
            abort(403, 'Acces non autorise.');
        }
        $dossier->load(['documents', 'user']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.dossier', ['dossier' => $dossier])
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);
        $filename = 'dossier-' . str_pad($dossier->id, 5, '0', STR_PAD_LEFT) . '-' . Str::slug($dossier->proprietaire) . '.pdf';
        return $pdf->download($filename);
    })->name('pdf.dossier');

    // Outils topographiques
    Route::get('outils', [OutilsTopoController::class, 'index'])->name('outils.index');
    Route::post('outils/distance', [OutilsTopoController::class, 'calculerDistance'])->name('outils.distance');
    Route::post('outils/surface', [OutilsTopoController::class, 'calculerSurface'])->name('outils.surface');
    Route::post('outils/convertir', [OutilsTopoController::class, 'convertirUnites'])->name('outils.convertir');
    Route::post('outils/tantiemes', [OutilsTopoController::class, 'calculerTantiemes'])->name('outils.tantiemes');

    // Chatbot
    Route::get('chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('chatbot/repondre', [ChatbotController::class, 'repondre'])->name('chatbot.repondre');
});

require __DIR__.'/auth.php';
