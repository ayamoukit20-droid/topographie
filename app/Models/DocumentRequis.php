<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequis extends Model
{
    use HasFactory;

    protected $table = 'documents_requis';

    protected $fillable = ['type_dossier_id', 'nom', 'categorie', 'obligatoire', 'ordre'];

    protected $casts = ['obligatoire' => 'boolean'];

    // Appartient a un type de dossier
    public function typeDossier()
    {
        return $this->belongsTo(TypeDossier::class, 'type_dossier_id');
    }
}
