<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeDossier extends Model
{
    use HasFactory;

    protected $table = 'types_dossiers';

    protected $fillable = ['code', 'nom', 'description', 'couleur', 'ordre'];

    // Un type a plusieurs documents requis
    public function documentsRequis()
    {
        return $this->hasMany(DocumentRequis::class, 'type_dossier_id')
                    ->orderBy('ordre');
    }

    // Un type a plusieurs dossiers
    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'type_dossier_id');
    }

    // Couleur du badge en CSS
    public function getBadgeCssAttribute(): string
    {
        return match($this->code) {
            'immatriculation' => 'rgba(34,197,94,0.15)',
            'maj'             => 'rgba(245,158,11,0.15)',
            'copropriete'     => 'rgba(59,130,246,0.15)',
            'morcellement'    => 'rgba(239,68,68,0.15)',
            'lotissement'     => 'rgba(168,85,247,0.15)',
            default           => 'rgba(100,116,139,0.15)',
        };
    }

    public function getBorderCssAttribute(): string
    {
        return match($this->code) {
            'immatriculation' => 'rgba(34,197,94,0.35)',
            'maj'             => 'rgba(245,158,11,0.35)',
            'copropriete'     => 'rgba(59,130,246,0.35)',
            'morcellement'    => 'rgba(239,68,68,0.35)',
            'lotissement'     => 'rgba(168,85,247,0.35)',
            default           => 'rgba(100,116,139,0.35)',
        };
    }

    public function getTextCssAttribute(): string
    {
        return match($this->code) {
            'immatriculation' => '#4ade80',
            'maj'             => '#fbbf24',
            'copropriete'     => '#60a5fa',
            'morcellement'    => '#f87171',
            'lotissement'     => '#c084fc',
            default           => '#94a3b8',
        };
    }
}
