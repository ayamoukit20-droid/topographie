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
        $isAdmin = $user->isAdmin();

        $baseQuery = $isAdmin ? Dossier::query() : $user->dossiers();

        $totalDossiers  = $baseQuery->count();
        $enCours        = (clone $baseQuery)->enCours()->count();
        $termines       = (clone $baseQuery)->termine()->count();

        $derniersDossiers = (clone $baseQuery)
            ->with(['typeDossier', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Stats par type dynamiques desde la DB
        $parType = (clone $baseQuery)
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

        // Tous les types disponibles
        $allTypes = TypeDossier::orderBy('ordre')->get();

        return view('dashboard', compact(
            'totalDossiers', 'enCours', 'termines',
            'derniersDossiers', 'parType', 'allTypes', 'isAdmin'
        ));
    }
}
