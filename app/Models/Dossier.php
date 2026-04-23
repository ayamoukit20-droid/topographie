<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type_dossier_id',
        'type_dossier',
        'proprietaire',
        'description',
        'date_creation',
        'statut',
        'localisation',
        'lat',
        'lng',
        'bornes',
    ];

    protected $casts = [
        'date_creation' => 'date',
        'lat'           => 'float',
        'lng'           => 'float',
        'bornes'        => 'array',
    ];

    // ── RELATIONS ──────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /** Relation vers le type de dossier (FK) */
    public function typeDossier()
    {
        return $this->belongsTo(TypeDossier::class, 'type_dossier_id');
    }

    // ── SCOPES ──────────────────────────────────────

    public function scopeEnCours($query)  { return $query->where('statut', 'en_cours'); }
    public function scopeTermine($query)  { return $query->where('statut', 'termine'); }

    // ── ACCESSEURS STATUT ───────────────────────────

    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'en_cours' => 'En cours',
            'termine'  => 'Termine',
            default    => $this->statut,
        };
    }

    public function getStatutBadgeAttribute(): string
    {
        return match($this->statut) {
            'en_cours' => 'warning',
            'termine'  => 'success',
            default    => 'secondary',
        };
    }

    // ── ACCESSEURS TYPE (dynamiques via FK ou fallback string) ──

    /** Retourne le nom complet du type */
    public function getTypeLabelAttribute(): string
    {
        // Priorité : relation FK (depuis DB)
        if ($this->relationLoaded('typeDossier') && $this->typeDossier) {
            return $this->typeDossier->nom;
        }
        // Fallback : code string
        return match($this->type_dossier ?? '') {
            'immatriculation' => 'Immatriculation Fonciere',
            'maj'             => 'Mise a Jour (MAJ)',
            'copropriete'     => 'Copropriete',
            'morcellement'    => 'Morcellement',
            'lotissement'     => 'Lotissement',
            default           => ucfirst($this->type_dossier ?? ''),
        };
    }

    /** Retourne la couleur CSS du badge */
    public function getTypeBadgeCssAttribute(): string
    {
        if ($this->relationLoaded('typeDossier') && $this->typeDossier) {
            return $this->typeDossier->badge_css;
        }
        return match($this->type_dossier ?? '') {
            'immatriculation' => 'rgba(34,197,94,0.15)',
            'maj'             => 'rgba(245,158,11,0.15)',
            'copropriete'     => 'rgba(59,130,246,0.15)',
            'morcellement'    => 'rgba(239,68,68,0.15)',
            'lotissement'     => 'rgba(168,85,247,0.15)',
            default           => 'rgba(100,116,139,0.15)',
        };
    }

    public function getTypeTextCssAttribute(): string
    {
        if ($this->relationLoaded('typeDossier') && $this->typeDossier) {
            return $this->typeDossier->text_css;
        }
        return match($this->type_dossier ?? '') {
            'immatriculation' => '#4ade80',
            'maj'             => '#fbbf24',
            'copropriete'     => '#60a5fa',
            'morcellement'    => '#f87171',
            'lotissement'     => '#c084fc',
            default           => '#94a3b8',
        };
    }

    /** Retourne la checklist depuis la DB (priorité) ou fallback array */
    public function getChecklistAttribute(): array
    {
        // Depuis la base via relation
        if ($this->relationLoaded('typeDossier') && $this->typeDossier) {
            $docs = $this->typeDossier->documentsRequis;
            if ($docs->isNotEmpty()) {
                return $docs->pluck('nom')->toArray();
            }
        }

        // Fallback statique
        return match($this->type_dossier ?? '') {
            'immatriculation' => [
                'Requisition d\'immatriculation', 'PV de bornage', 'Plan de bornage',
                'Plan de situation', 'Tableau de coordonnees (X,Y)', 'Calcul de contenance',
            ],
            'maj' => [
                'Titre foncier', 'Autorisation de construire', 'Plan architecte vise',
                'Permis d\'habiter', 'Plan de situation', 'Plan de mise a jour', 'Calcul surface batie',
            ],
            'copropriete' => [
                'Titre foncier', 'Certificat de propriete', 'Autorisation de construire',
                'Certificat de conformite', 'Note de renseignements', 'Plan de situation',
                'Plan de masse', 'Plans architecturaux (RDC + tous etages)',
                'Tableau des surfaces (Tableau A)', 'Tableau des tantiemes (Tableau B)',
                'Reglement de copropriete', 'Etat descriptif de division',
            ],
            'morcellement' => [
                'Note de renseignements', 'Autorisation de division', 'Plan de division',
                'Calcul des nouvelles surfaces', 'Plan cadastral',
            ],
            'lotissement' => [
                'Plan de lotissement general', 'Cahier des charges', 'Plan de masse',
                'Plan voirie et reseaux (VRD)', 'PV de reception des travaux', 'Permis de lotir',
            ],
            default => [],
        };
    }
}
