@extends('layouts.app')

@section('title', 'Admin — Modifier le dossier #' . str_pad($dossier->id, 5, '0', STR_PAD_LEFT))
@section('page-title', 'Modifier le dossier (Admin)')

@section('content')
<div class="breadcrumb-topo mb-4">
    <a href="{{ route('dashboard') }}">Accueil</a>
    <span class="sep">/</span>
    <a href="{{ route('admin.users.index') }}">Administration</a>
    <span class="sep">/</span>
    <a href="{{ route('admin.dossiers.index') }}">Dossiers</a>
    <span class="sep">/</span>
    <a href="{{ route('admin.dossiers.show', $dossier) }}">#{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }}</a>
    <span class="sep">/</span>
    <span>Modifier</span>
</div>

<div class="row g-4 justify-content-center">
    <div class="col-lg-8">
        <div class="card-topo">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:24px;padding-bottom:18px;border-bottom:1px solid var(--border);">
                <div style="width:40px;height:40px;background:rgba(249,115,22,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(249,115,22,0.3);">
                    <i class="bi bi-pencil-square" style="color:#f97316;font-size:18px;"></i>
                </div>
                <div>
                    <h5 style="font-size:17px;font-weight:700;margin:0;color:var(--white);">Modification Admin</h5>
                    <p style="font-size:12px;color:var(--text-muted);margin:0;">Dossier #{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }} — {{ $dossier->proprietaire }}</p>
                </div>
                <span style="margin-left:auto;background:rgba(249,115,22,0.12);color:#f97316;border:1px solid rgba(249,115,22,0.3);border-radius:6px;font-size:10px;padding:3px 10px;font-weight:700;display:flex;align-items:center;gap:4px;">
                                    <i class="bi bi-shield-lock-fill"></i> ADMIN
                                </span>
            </div>

            <form method="POST" action="{{ route('admin.dossiers.update', $dossier) }}">
                @csrf @method('PUT')

                <div class="row g-3">
                    {{-- Type de dossier --}}
                    <div class="col-md-6">
                        <label class="form-label-topo">Type de dossier *</label>
                        <select name="type_dossier_id" class="form-select-topo" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ $dossier->type_dossier_id == $type->id ? 'selected' : '' }}>
                                    {{ $type->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('type_dossier_id') <div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
                    </div>

                    {{-- Propriétaire --}}
                    <div class="col-md-6">
                        <label class="form-label-topo">Propriétaire *</label>
                        <input type="text" name="proprietaire" value="{{ old('proprietaire', $dossier->proprietaire) }}"
                               class="form-control-topo" required>
                        @error('proprietaire') <div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
                    </div>

                    {{-- Topographe (réassignation) --}}
                    <div class="col-md-6">
                        <label class="form-label-topo">
                            <i class="bi bi-person-gear" style="color:#f97316;"></i>
                            Topographe propriétaire *
                        </label>
                        <select name="user_id" class="form-select-topo" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $dossier->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                    {{ $user->isAdmin() ? '— Admin' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
                    </div>

                    {{-- Statut --}}
                    <div class="col-md-6">
                        <label class="form-label-topo">Statut *</label>
                        <select name="statut" class="form-select-topo" required>
                            <option value="en_cours" {{ $dossier->statut === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="termine"  {{ $dossier->statut === 'termine'  ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>

                    {{-- Date --}}
                    <div class="col-md-6">
                        <label class="form-label-topo">Date de création *</label>
                        <input type="date" name="date_creation"
                               value="{{ old('date_creation', $dossier->date_creation->format('Y-m-d')) }}"
                               class="form-control-topo" required>
                    </div>

                    {{-- Localisation --}}
                    <div class="col-md-6">
                        <label class="form-label-topo">Localisation</label>
                        <input type="text" name="localisation" value="{{ old('localisation', $dossier->localisation) }}"
                               class="form-control-topo" placeholder="Ville, quartier...">
                    </div>

                    {{-- GPS --}}
                    <div class="col-md-6">
                        <label class="form-label-topo">Latitude</label>
                        <input type="number" name="lat" value="{{ old('lat', $dossier->lat) }}"
                               step="0.000001" class="form-control-topo" placeholder="Ex: 33.589886">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-topo">Longitude</label>
                        <input type="number" name="lng" value="{{ old('lng', $dossier->lng) }}"
                               step="0.000001" class="form-control-topo" placeholder="Ex: -7.603869">
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label class="form-label-topo">Description</label>
                        <textarea name="description" class="form-control-topo" rows="4"
                                  placeholder="Description des travaux topographiques...">{{ old('description', $dossier->description) }}</textarea>
                    </div>

                    {{-- Boutons --}}
                    <div class="col-12" style="display:flex;gap:12px;padding-top:8px;border-top:1px solid var(--border);margin-top:8px;">
                        <button type="submit" class="btn-orange">
                            <i class="bi bi-check-lg"></i> Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.dossiers.show', $dossier) }}" class="btn-outline-orange">
                            <i class="bi bi-x-lg"></i> Annuler
                        </a>
                        <a href="{{ route('admin.dossiers.index') }}" style="display:flex;align-items:center;gap:6px;padding:9px 14px;color:var(--text-muted);text-decoration:none;font-size:13px;margin-left:auto;">
                            <i class="bi bi-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
