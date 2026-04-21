@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')
@section('page-title', 'Gestion des Utilisateurs')

@section('content')
<div class="breadcrumb-topo">
    <a href="{{ route('dashboard') }}">Accueil</a>
    <span class="sep">/</span>
    <span>Administration</span>
    <span class="sep">/</span>
    <span>Utilisateurs</span>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="padding:15px 20px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="stat-icon" style="margin-bottom:0;width:38px;height:38px;font-size:16px;"><i class="bi bi-people"></i></div>
                <div>
                    <div class="stat-value" style="font-size:22px;">{{ $users->total() }}</div>
                    <div class="stat-label" style="margin-top:2px;font-size:11px;">Utilisateurs inscrits</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="padding:15px 20px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="stat-icon" style="margin-bottom:0;width:38px;height:38px;font-size:16px;background:rgba(245,158,11,0.1);color:#fbbf24;"><i class="bi bi-shield-check"></i></div>
                <div>
                    <div class="stat-value" style="font-size:22px;">{{ \App\Models\User::where('role', 'admin')->count() }}</div>
                    <div class="stat-label" style="margin-top:2px;font-size:11px;">Administrateurs</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="padding:15px 20px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="stat-icon" style="margin-bottom:0;width:38px;height:38px;font-size:16px;background:rgba(34,197,94,0.1);color:#4ade80;"><i class="bi bi-folder-check"></i></div>
                <div>
                    <div class="stat-value" style="font-size:22px;">{{ \App\Models\Dossier::count() }}</div>
                    <div class="stat-label" style="margin-top:2px;font-size:11px;">Dossiers totaux</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-topo">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <div>
            <h6 style="font-size:16px;font-weight:700;margin:0;color:var(--white);">Liste des utilisateurs</h6>
            <p style="font-size:12px;color:var(--text-muted);margin:4px 0 0;">Gérez les accès et les rôles de la plateforme.</p>
        </div>
        <div style="font-size:13px;color:var(--text-muted);">
            Total : <strong style="color:var(--white);">{{ $users->total() }}</strong> utilisateurs
        </div>
    </div>

    @if($users->isEmpty())
        <div style="text-align:center;padding:40px;color:var(--text-muted);">
            <i class="bi bi-people" style="font-size:40px;opacity:0.3;display:block;margin-bottom:10px;"></i>
            Aucun utilisateur trouvé.
        </div>
    @else
        <div class="table-responsive">
            <table class="table-topo">
                <thead>
                    <tr>
                        <th>Nom / Email</th>
                        <th>Rôle</th>
                        <th>Spécialité</th>
                        <th>Dossiers</th>
                        <th>Inscription</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div style="width:34px;height:34px;background:{{ $user->isAdmin() ? 'rgba(245,158,11,0.2)' : 'rgba(59,130,246,0.15)' }};border-radius:50%;display:flex;align-items:center;justify-content:center;color:{{ $user->isAdmin() ? '#fbbf24' : '#60a5fa' }};font-weight:700;font-size:14px;border:1px solid {{ $user->isAdmin() ? 'rgba(245,158,11,0.3)' : 'rgba(59,130,246,0.3)' }};">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;color:var(--white);">{{ $user->name }}</div>
                                    <div style="font-size:11px;color:var(--text-muted);">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($user->isAdmin())
                                <span class="badge-topo" style="background:rgba(245,158,11,0.15);color:#fbbf24;border:1px solid rgba(245,158,11,0.25);">Admin</span>
                            @else
                                <span class="badge-topo" style="background:rgba(148,163,184,0.12);color:#94a3b8;border:1px solid rgba(148,163,184,0.2);">Utilisateur</span>
                            @endif
                        </td>
                        <td style="font-size:13px;color:var(--text-muted);">
                            {{ $user->specialite ?? 'Non définie' }}
                        </td>
                        <td>
                            <span style="font-weight:700;color:var(--white);">{{ $user->dossiers_count }}</span>
                        </td>
                        <td style="font-size:12px;color:var(--text-muted);">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;justify-content:flex-end;gap:8px;">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn-outline-orange" style="padding:5px 10px;font-size:12px;">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ? Cette action est irréversible.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="padding:5px 9px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;border-radius:8px;font-size:12px;cursor:pointer;">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
