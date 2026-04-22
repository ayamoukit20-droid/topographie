<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class PdfController extends Controller
{
    /**
     * Vérifie que l'utilisateur a accès au dossier
     */
    private function authorize(Dossier $dossier): void
    {
        $user = auth()->user();
        if ($dossier->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }
    }

    /**
     * 📄 PV de Bornage
     */
    public function pv(Dossier $dossier)
    {
        $this->authorize($dossier);
        $dossier->load(['documents', 'typeDossier', 'user']);

        $data = ['dossier' => $dossier];

        $pdf = Pdf::loadView('pdf.pv', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);

        $filename = 'PV-bornage-' . str_pad($dossier->id, 5, '0', STR_PAD_LEFT)
            . '-' . Str::slug($dossier->proprietaire) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * 📄 Rapport Technique
     */
    public function rapport(Dossier $dossier)
    {
        $this->authorize($dossier);
        $dossier->load(['documents', 'typeDossier.documentsRequis', 'user']);

        $data = ['dossier' => $dossier];

        $pdf = Pdf::loadView('pdf.rapport', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);

        $filename = 'Rapport-technique-' . str_pad($dossier->id, 5, '0', STR_PAD_LEFT)
            . '-' . Str::slug($dossier->proprietaire) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * 📄 Fiche Dossier
     */
    public function fiche(Dossier $dossier)
    {
        $this->authorize($dossier);
        $dossier->load(['documents', 'typeDossier.documentsRequis', 'user']);

        $data = ['dossier' => $dossier];

        $pdf = Pdf::loadView('pdf.fiche', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);

        $filename = 'Fiche-dossier-' . str_pad($dossier->id, 5, '0', STR_PAD_LEFT)
            . '-' . Str::slug($dossier->proprietaire) . '.pdf';

        return $pdf->download($filename);
    }
}
