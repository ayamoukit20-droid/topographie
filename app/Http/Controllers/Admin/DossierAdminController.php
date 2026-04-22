<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\TypeDossier;
use Illuminate\Http\Request;

class DossierAdminController extends Controller
{
    /**
     * Liste TOUS les dossiers de tous les utilisateurs
     */
    public function index(Request $request)
    {
        $query = Dossier::with(['typeDossier', 'user', 'documents'])
            ->withCount('documents')
            ->latest();

        // Filtres
        if ($request->filled('type')) {
            $query->where(function ($q) use ($request) {
                $q->where('type_dossier', $request->type)
                  ->orWhereHas('typeDossier', fn ($tq) => $tq->where('code', $request->type));
            });
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('proprietaire', 'like', "%$search%")
                  ->orWhere('description',  'like', "%$search%")
                  ->orWhere('localisation', 'like', "%$search%")
                  ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%$search%"));
            });
        }

        $dossiers = $query->paginate(15)->withQueryString();
        $types    = TypeDossier::orderBy('ordre')->get();
        $users    = \App\Models\User::orderBy('name')->get();

        // Statistiques globales
        $stats = [
            'total'    => Dossier::count(),
            'en_cours' => Dossier::where('statut', 'en_cours')->count(),
            'termine'  => Dossier::where('statut', 'termine')->count(),
            'today'    => Dossier::whereDate('created_at', today())->count(),
        ];

        return view('admin.dossiers.index', compact('dossiers', 'types', 'users', 'stats'));
    }

    /**
     * Détail d'un dossier (vue admin)
     */
    public function show(Dossier $dossier)
    {
        $dossier->load(['documents', 'typeDossier.documentsRequis', 'user']);
        return view('admin.dossiers.show', compact('dossier'));
    }

    /**
     * Formulaire d'édition admin
     */
    public function edit(Dossier $dossier)
    {
        $types = TypeDossier::with('documentsRequis')->orderBy('ordre')->get();
        $users = \App\Models\User::orderBy('name')->get();
        return view('admin.dossiers.edit', compact('dossier', 'types', 'users'));
    }

    /**
     * Mise à jour (admin peut changer le propriétaire/user)
     */
    public function update(Request $request, Dossier $dossier)
    {
        $validated = $request->validate([
            'type_dossier_id' => 'required|exists:types_dossiers,id',
            'proprietaire'    => 'required|string|max:255',
            'description'     => 'nullable|string',
            'date_creation'   => 'required|date',
            'statut'          => 'required|in:en_cours,termine',
            'localisation'    => 'nullable|string|max:255',
            'lat'             => 'nullable|numeric|between:-90,90',
            'lng'             => 'nullable|numeric|between:-180,180',
            'user_id'         => 'required|exists:users,id',
        ]);

        $type = TypeDossier::find($validated['type_dossier_id']);
        $validated['type_dossier'] = $type->code;

        $dossier->update($validated);

        return redirect()->route('admin.dossiers.index')
            ->with('success', "Dossier #{$dossier->id} mis à jour avec succès.");
    }

    /**
     * Suppression (admin, avec fichiers physiques)
     */
    public function destroy(Dossier $dossier)
    {
        $dossierId = $dossier->id;
        $owner     = $dossier->proprietaire;

        foreach ($dossier->documents as $doc) {
            if (file_exists(storage_path('app/public/' . $doc->fichier))) {
                unlink(storage_path('app/public/' . $doc->fichier));
            }
        }

        $dossier->delete();

        return redirect()->route('admin.dossiers.index')
            ->with('success', "Dossier #{$dossierId} ({$owner}) supprimé définitivement.");
    }
}
