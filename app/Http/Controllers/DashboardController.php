<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\TypeDossier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = auth()->user();
        $isAdmin = $user->isAdmin();

        // ── Statistiques selon le rôle ─────────────────────────────────────
        if ($isAdmin) {
            return $this->adminDashboard();
        }

        return $this->userDashboard($user);
    }

    // ── Dashboard Administrateur ────────────────────────────────────────────
    private function adminDashboard()
    {
        $isAdmin = true;

        // Stats globales
        $stats = [
            'total_users'      => User::count(),
            'total_topographes'=> User::where('role', 'user')->count(),
            'total_admins'     => User::where('role', 'admin')->count(),
            'total_dossiers'   => Dossier::count(),
            'dossiers_en_cours'=> Dossier::where('statut', 'en_cours')->count(),
            'dossiers_termines' => Dossier::where('statut', 'termine')->count(),
            'total_documents'  => \App\Models\Document::count(),
            'dossiers_today'   => Dossier::whereDate('created_at', today())->count(),
            'dossiers_month'   => Dossier::whereMonth('created_at', now()->month)
                                          ->whereYear('created_at', now()->year)->count(),
            'types_count'      => TypeDossier::count(),
        ];

        // Évolution mensuelle des dossiers (12 derniers mois)
        $evolution = Dossier::selectRaw('MONTH(created_at) as mois, YEAR(created_at) as annee, COUNT(*) as total')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('annee', 'mois')
            ->orderBy('annee')->orderBy('mois')
            ->get()
            ->keyBy(fn($r) => $r->annee . '-' . str_pad($r->mois, 2, '0', STR_PAD_LEFT));

        $evolutionLabels = [];
        $evolutionData   = [];
        $moisFr = ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Août','Sep','Oct','Nov','Déc'];
        for ($i = 11; $i >= 0; $i--) {
            $date  = now()->subMonths($i);
            $key   = $date->format('Y-m');
            $evolutionLabels[] = $moisFr[$date->month - 1] . ' ' . $date->format('y');
            $evolutionData[]   = $evolution[$key]->total ?? 0;
        }

        // Répartition par type (pour doughnut)
        $parType = Dossier::selectRaw('type_dossier_id, type_dossier, COUNT(*) as total')
            ->groupBy('type_dossier_id', 'type_dossier')
            ->with('typeDossier')
            ->get()
            ->map(fn($item) => [
                'nom'   => $item->typeDossier?->nom ?? ucfirst($item->type_dossier),
                'total' => $item->total,
                'color' => $item->typeDossier?->text_css ?? '#94a3b8',
            ]);

        // Top topographes (les plus actifs)
        $topTopographes = User::withCount('dossiers')
            ->orderByDesc('dossiers_count')
            ->take(5)
            ->get();

        // Activité récente (tous les utilisateurs)
        $derniersDossiers = Dossier::with(['typeDossier', 'user'])
            ->latest()
            ->take(8)
            ->get();

        // Dossiers géolocalisés (carte)
        $dossiersCarte = Dossier::whereNotNull('lat')->whereNotNull('lng')
            ->with(['typeDossier'])
            ->get(['id', 'proprietaire', 'type_dossier', 'type_dossier_id',
                   'statut', 'lat', 'lng', 'localisation', 'date_creation']);

        $allTypes = TypeDossier::orderBy('ordre')->get();

        return view('dashboard', compact(
            'isAdmin', 'stats', 'evolutionLabels', 'evolutionData',
            'parType', 'topTopographes', 'derniersDossiers',
            'dossiersCarte', 'allTypes'
        ));
    }

    // ── Dashboard Utilisateur (Topographe) ─────────────────────────────────
    private function userDashboard(User $user)
    {
        $isAdmin = false;
        $baseQuery = $user->dossiers();

        $totalDossiers = $baseQuery->count();
        $enCours       = (clone $baseQuery)->enCours()->count();
        $termines      = (clone $baseQuery)->termine()->count();

        $derniersDossiers = (clone $baseQuery)
            ->with(['typeDossier', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $parType = (clone $baseQuery)
            ->selectRaw('type_dossier_id, type_dossier, COUNT(*) as total')
            ->groupBy('type_dossier_id', 'type_dossier')
            ->with('typeDossier')
            ->get()
            ->map(fn($item) => [
                'nom'   => $item->typeDossier?->nom ?? ucfirst($item->type_dossier),
                'code'  => $item->type_dossier,
                'total' => $item->total,
                'color' => $item->typeDossier?->text_css ?? '#94a3b8',
            ]);

        $allTypes = TypeDossier::orderBy('ordre')->get();

        $dossiersCarte = (clone $baseQuery)
            ->whereNotNull('lat')->whereNotNull('lng')
            ->with(['typeDossier'])
            ->get(['id', 'proprietaire', 'type_dossier', 'type_dossier_id',
                   'statut', 'lat', 'lng', 'localisation', 'date_creation']);

        // Stats simples pour l'utilisateur
        $stats = null;
        $evolutionLabels = $evolutionData = $topTopographes = [];

        return view('dashboard', compact(
            'isAdmin', 'totalDossiers', 'enCours', 'termines',
            'derniersDossiers', 'parType', 'allTypes',
            'dossiersCarte', 'stats', 'evolutionLabels', 'evolutionData',
            'topTopographes'
        ));
    }
}
