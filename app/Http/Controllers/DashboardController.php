<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\TypeDossier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalDossiers  = $user->dossiers()->count();
        $enCours        = $user->dossiers()->enCours()->count();
        $valides        = $user->dossiers()->valide()->count();
        $termines       = $user->dossiers()->termine()->count();

        $derniersDossiers = $user->dossiers()
            ->with(['typeDossier'])
            ->latest()
            ->take(5)
            ->get();

        // Stats par type dynamiques depuis la DB
        $parType = $user->dossiers()
            ->selectRaw('type_dossier_id, type_dossier, COUNT(*) as total')
            ->groupBy('type_dossier_id', 'type_dossier')
            ->with('typeDossier')
            ->get()
            ->map(function($item) {
                return [
                    'nom'    => $item->typeDossier?->nom ?? ucfirst($item->type_dossier),
                    'code'   => $item->type_dossier,
                    'total'  => $item->total,
                    'badge'  => $item->typeDossier?->badge_css  ?? 'rgba(100,116,139,0.15)',
                    'border' => $item->typeDossier?->border_css ?? 'rgba(100,116,139,0.35)',
                    'color'  => $item->typeDossier?->text_css   ?? '#94a3b8',
                ];
            });

        // Tous les types disponibles (pour le graphique complet)
        $allTypes = TypeDossier::orderBy('ordre')->get();

        return view('dashboard', compact(
            'totalDossiers', 'enCours', 'valides', 'termines',
            'derniersDossiers', 'parType', 'allTypes'
        ));
    }
}
