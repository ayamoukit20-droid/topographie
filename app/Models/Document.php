<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'dossier_id',
        'nom',
        'type_document',
        'fichier',
        'taille',
        'extension',
    ];

    // Relations
    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }

    // Helpers
    public function getIconAttribute(): string
    {
        return match($this->extension) {
            'pdf'        => 'bi-file-earmark-pdf text-danger',
            'doc', 'docx'=> 'bi-file-earmark-word text-primary',
            'xls', 'xlsx'=> 'bi-file-earmark-excel text-success',
            'jpg', 'jpeg', 'png' => 'bi-file-earmark-image text-warning',
            'zip', 'rar' => 'bi-file-earmark-zip text-secondary',
            default      => 'bi-file-earmark text-muted',
        };
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->fichier);
    }
}
