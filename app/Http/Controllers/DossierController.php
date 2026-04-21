<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\TypeDossier;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DossierController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = $user->isAdmin() 
            ? Dossier::query() 
            : $user->dossiers();
            
        $query->with(['typeDossier', 'user', 'documents'])
            ->withCount('documents')
            ->latest();

        if ($request->filled('type')) {
            // Recherche par code OU par id
            $query->where(function($q) use ($request) {
                $q->where('type_dossier', $request->type)
                  ->orWhereHas('typeDossier', fn($tq) => $tq->where('code', $request->type));
            });
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('proprietaire', 'like', "%$search%")
                  ->orWhere('description',  'like', "%$search%")
                  ->orWhere('localisation', 'like', "%$search%");
            });
        }

        $dossiers = $query->paginate(10);
        $types    = TypeDossier::orderBy('ordre')->get();

        return view('dossiers.index', compact('dossiers', 'types'));
    }

    public function create()
    {
        $types = TypeDossier::with('documentsRequis')->orderBy('ordre')->get();
        return view('dossiers.create', compact('types'));
    }

    public function store(Request $request)
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
        ], [
            'type_dossier_id.required' => 'Veuillez selectionner un type de dossier.',
            'type_dossier_id.exists'   => 'Type de dossier invalide.',
            'proprietaire.required'    => 'Le nom du proprietaire est obligatoire.',
            'date_creation.required'   => 'La date de creation est obligatoire.',
        ]);

        // Synchroniser le code string (type_dossier) depuis la FK
        $type = TypeDossier::find($validated['type_dossier_id']);
        $validated['type_dossier'] = $type->code;
        $validated['user_id']      = auth()->id();

        $dossier = Dossier::create($validated);

        return redirect()->route('dossiers.show', $dossier)
            ->with('success', 'Dossier cree avec succes !');
    }

    public function show(Dossier $dossier)
    {
        $this->authorize('view', $dossier);
        $dossier->load(['documents', 'typeDossier.documentsRequis', 'user']);
        return view('dossiers.show', compact('dossier'));
    }

    public function edit(Dossier $dossier)
    {
        $this->authorize('update', $dossier);
        $types = TypeDossier::with('documentsRequis')->orderBy('ordre')->get();
        return view('dossiers.edit', compact('dossier', 'types'));
    }

    public function update(Request $request, Dossier $dossier)
    {
        $this->authorize('update', $dossier);

        $validated = $request->validate([
            'type_dossier_id' => 'required|exists:types_dossiers,id',
            'proprietaire'    => 'required|string|max:255',
            'description'     => 'nullable|string',
            'date_creation'   => 'required|date',
            'statut'          => 'required|in:en_cours,termine',
            'localisation'    => 'nullable|string|max:255',
            'lat'             => 'nullable|numeric|between:-90,90',
            'lng'             => 'nullable|numeric|between:-180,180',
        ]);

        $type = TypeDossier::find($validated['type_dossier_id']);
        $validated['type_dossier'] = $type->code;

        $dossier->update($validated);

        return redirect()->route('dossiers.show', $dossier)
            ->with('success', 'Dossier mis a jour avec succes !');
    }

    public function destroy(Dossier $dossier)
    {
        $this->authorize('delete', $dossier);

        foreach ($dossier->documents as $doc) {
            if (file_exists(storage_path('app/public/' . $doc->fichier))) {
                unlink(storage_path('app/public/' . $doc->fichier));
            }
        }

        $dossier->delete();

        return redirect()->route('dossiers.index')
            ->with('success', 'Dossier supprime avec succes !');
    }
}
