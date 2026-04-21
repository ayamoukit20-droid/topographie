<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Dossier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, Dossier $dossier)
    {
        $this->authorize('update', $dossier);

        $request->validate([
            'nom'           => 'required|string|max:255',
            'type_document' => 'required|string|max:255',
            'fichier'       => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar',
        ]);

        $file = $request->file('fichier');
        $extension = $file->getClientOriginalExtension();
        $taille = $this->formatTaille($file->getSize());
        $path = $file->store('documents', 'public');

        Document::create([
            'dossier_id'    => $dossier->id,
            'nom'           => $request->nom,
            'type_document' => $request->type_document,
            'fichier'       => $path,
            'taille'        => $taille,
            'extension'     => $extension,
        ]);

        return redirect()->route('dossiers.show', $dossier)
            ->with('success', 'Document ajouté avec succès !');
    }

    public function destroy(Document $document)
    {
        $dossier = $document->dossier;
        $this->authorize('update', $dossier);

        // Supprimer le fichier physique
        Storage::disk('public')->delete($document->fichier);

        $document->delete();

        return redirect()->route('dossiers.show', $dossier)
            ->with('success', 'Document supprimé avec succès !');
    }

    private function formatTaille(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
