@extends('layouts.app')

@section('title', 'Modifier l\'utilisateur')
@section('page-title', 'Modifier l\'utilisateur')

@section('content')
<div class="breadcrumb-topo">
    <a href="{{ route('dashboard') }}">Accueil</a>
    <span class="sep">/</span>
    <a href="{{ route('admin.users.index') }}">Utilisateurs</a>
    <span class="sep">/</span>
    <span>Modifier</span>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card-topo">
            <h6 style="font-size:16px;font-weight:700;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid var(--border);">
                <i class="bi bi-person-gear" style="color:#fbbf24;margin-right:8px;"></i>
                Édition de l'utilisateur
            </h6>

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-topo">Nom complet *</label>
                        <input type="text" name="name" class="form-control-topo" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-topo">Email *</label>
                        <input type="email" name="email" class="form-control-topo" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-topo">Rôle *</label>
                        <select name="role" class="form-select-topo" required>
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Utilisateur Standard</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-topo">Téléphone</label>
                        <input type="text" name="telephone" class="form-control-topo" value="{{ old('telephone', $user->telephone) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-topo">Spécialité</label>
                        <input type="text" name="specialite" class="form-control-topo" value="{{ old('specialite', $user->specialite) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-topo">Organisation / Entreprise</label>
                        <input type="text" name="organisation" class="form-control-topo" value="{{ old('organisation', $user->organisation) }}">
                    </div>
                </div>

                <div class="mt-4 d-flex gap-3">
                    <button type="submit" class="btn-orange">
                        <i class="bi bi-check2-circle"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-outline-orange">
                        <i class="bi bi-x-lg"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card-topo mb-4">
            <h6 style="font-size:14px;font-weight:700;margin-bottom:16px;">Statistiques de l'utilisateur</h6>
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:13px;color:var(--text-muted);">Dossiers créés</span>
                    <span style="font-size:18px;font-weight:800;color:#60a5fa;">{{ $user->dossiers()->count() }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:13px;color:var(--text-muted);">Inscrit depuis</span>
                    <span style="font-size:13px;font-weight:600;color:var(--white);">{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <div class="card-topo" style="border-left:3px solid #f87171;">
            <h6 style="font-size:14px;font-weight:700;color:#f87171;margin-bottom:10px;">Attention</h6>
            <p style="font-size:12px;color:var(--text-muted);line-height:1.6;margin:0;">
                Toute modification du rôle affecte immédiatement les permissions de l'utilisateur. 
                Supprimer un utilisateur supprimera également tous ses dossiers et documents associés si les relations en cascade sont activées.
            </p>
        </div>
    </div>
</div>
@endsection
