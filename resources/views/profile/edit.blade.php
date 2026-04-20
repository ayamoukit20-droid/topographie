@extends('layouts.app')

@section('title', 'Mon profil')
@section('page-title', 'Mon profil')

@section('content')
<div class="breadcrumb-topo">
    <a href="{{ route('dashboard') }}">Accueil</a>
    <span class="sep">/</span>
    <span>Profil</span>
</div>

<div class="row g-4">
    <!-- Profil principal -->
    <div class="col-lg-8">
        <div class="card-topo mb-4">
            <h6 style="font-size:16px;font-weight:700;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid var(--border);">
                <i class="bi bi-person-circle" style="color:var(--orange);margin-right:8px;"></i>
                Informations personnelles
            </h6>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf @method('PATCH')

                @if (session('status') === 'profile-updated')
                    <div class="alert-topo alert-success-topo mb-4">
                        <i class="bi bi-check-circle-fill"></i> Profil mis à jour avec succès !
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-topo">Nom complet *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="form-control-topo" required>
                        @error('name') <div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-topo">Adresse email *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="form-control-topo" required>
                        @error('email') <div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-topo">Téléphone</label>
                        <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}"
                               class="form-control-topo" placeholder="+213 6XX XXX XXX">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-topo">Spécialité</label>
                        <input type="text" name="specialite" value="{{ old('specialite', $user->specialite) }}"
                               class="form-control-topo" placeholder="Topographe géomètre...">
                    </div>

                    <div class="col-12">
                        <label class="form-label-topo">Organisation / Cabinet</label>
                        <input type="text" name="organisation" value="{{ old('organisation', $user->organisation) }}"
                               class="form-control-topo" placeholder="Nom du cabinet ou organisation">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn-orange">
                        <i class="bi bi-save2"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>

        <!-- Changer mot de passe -->
        <div class="card-topo">
            <h6 style="font-size:16px;font-weight:700;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid var(--border);">
                <i class="bi bi-shield-lock" style="color:var(--orange);margin-right:8px;"></i>
                Changer le mot de passe
            </h6>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf @method('PUT')

                @if (session('status') === 'password-updated')
                    <div class="alert-topo alert-success-topo mb-4">
                        <i class="bi bi-check-circle-fill"></i> Mot de passe modifié avec succès !
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label-topo">Mot de passe actuel</label>
                        <input type="password" name="current_password" class="form-control-topo" placeholder="••••••••">
                        @error('current_password') <div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-topo">Nouveau mot de passe</label>
                        <input type="password" name="password" class="form-control-topo" placeholder="Min. 8 caractères">
                        @error('password') <div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-topo">Confirmer</label>
                        <input type="password" name="password_confirmation" class="form-control-topo" placeholder="••••••••">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn-orange">
                        <i class="bi bi-key"></i> Changer le mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar profil -->
    <div class="col-lg-4">
        <div class="card-topo text-center mb-4">
            <div style="width:80px;height:80px;background:var(--orange);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:700;margin:0 auto 16px;box-shadow:0 8px 24px rgba(249,115,22,0.4);">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h6 style="font-size:18px;font-weight:700;margin-bottom:4px;">{{ $user->name }}</h6>
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:0;">{{ $user->specialite ?? 'Topographe' }}</p>
            @if($user->organisation)
                <p style="font-size:12px;color:var(--text-muted);margin-top:4px;">
                    <i class="bi bi-building" style="color:var(--orange);"></i>
                    {{ $user->organisation }}
                </p>
            @endif
        </div>

        <div class="card-topo mb-4">
            <h6 style="font-size:14px;font-weight:700;margin-bottom:16px;">Statistiques</h6>
            @php $stats = auth()->user()->dossiers(); @endphp
            <div style="display:flex;flex-direction:column;gap:12px;font-size:13px;">
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--text-muted);">Total dossiers</span>
                    <span style="color:var(--orange);font-weight:700;">{{ $stats->count() }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--text-muted);">En cours</span>
                    <span style="color:#fbbf24;font-weight:700;">{{ $stats->where('statut','en_cours')->count() }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--text-muted);">Terminés</span>
                    <span style="color:#22c55e;font-weight:700;">{{ $stats->where('statut','termine')->count() }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--text-muted);">Membre depuis</span>
                    <span>{{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
