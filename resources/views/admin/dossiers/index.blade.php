@extends('layouts.app')

@section('title', 'Admin — Gestion de tous les dossiers')
@section('page-title', 'Gestion Globale des Dossiers')

@section('content')

{{-- EN-TÊTE --}}
<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <div class="breadcrumb-topo mb-2">
            <a href="{{ route('dashboard') }}">Accueil</a>
            <span class="sep">/</span>
            <a href="{{ route('admin.users.index') }}">Administration</a>
            <span class="sep">/</span>
            <span>Dossiers</span>
        </div>
        <h2 style="font-size:24px;font-weight:800;color:var(--white);margin:0;letter-spacing:-0.5px;">
            Tous les Dossiers
        </h2>
        <p style="font-size:12px;color:var(--text-muted);margin-top:4px;">
            Vue globale — tous les utilisateurs confondus
        </p>
    </div>
    <div style="text-align:right;">
        <span style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;font-weight:700;">Mode Admin</span>
        <div class="d-flex align-items-center gap-2 mt-1">
            <div style="width:8px;height:8px;background:#f97316;border-radius:50%;box-shadow:0 0 10px #f97316;"></div>
            <span style="font-size:13px;font-weight:600;color:#f97316;">Accès complet</span>
        </div>
    </div>
</div>

{{-- STATISTIQUES --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="padding:14px 18px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="stat-icon" style="margin-bottom:0;width:38px;height:38px;font-size:16px;">
                    <i class="bi bi-folder2"></i>
                </div>
                <div>
                    <div class="stat-value" style="font-size:22px;">{{ $stats['total'] }}</div>
                    <div class="stat-label" style="font-size:11px;">Total dossiers</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="padding:14px 18px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="stat-icon" style="margin-bottom:0;width:38px;height:38px;font-size:16px;background:rgba(245,158,11,0.1);color:#fbbf24;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <div class="stat-value" style="font-size:22px;color:#fbbf24;">{{ $stats['en_cours'] }}</div>
                    <div class="stat-label" style="font-size:11px;">En cours</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="padding:14px 18px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="stat-icon" style="margin-bottom:0;width:38px;height:38px;font-size:16px;background:rgba(34,197,94,0.1);color:#4ade80;">
                    <i class="bi bi-folder-check"></i>
                </div>
                <div>
                    <div class="stat-value" style="font-size:22px;color:#4ade80;">{{ $stats['termine'] }}</div>
                    <div class="stat-label" style="font-size:11px;">Terminés</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="padding:14px 18px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="stat-icon" style="margin-bottom:0;width:38px;height:38px;font-size:16px;background:rgba(249,115,22,0.1);color:#f97316;">
                    <i class="bi bi-calendar-plus"></i>
                </div>
                <div>
                    <div class="stat-value" style="font-size:22px;color:#f97316;">{{ $stats['today'] }}</div>
                    <div class="stat-label" style="font-size:11px;">Créés aujourd'hui</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTRES --}}
<div class="card-topo mb-4">
    <form method="GET" action="{{ route('admin.dossiers.index') }}">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label-topo">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control-topo" placeholder="Propriétaire, lieu, description, utilisateur...">
            </div>
            <div class="col-md-2">
                <label class="form-label-topo">Type de dossier</label>
                <select name="type" class="form-select-topo">
                    <option value="">Tous les types</option>
                    @foreach($types as $type)
                        <option value="{{ $type->code }}" {{ request('type') === $type->code ? 'selected' : '' }}>
                            {{ $type->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label-topo">Statut</label>
                <select name="statut" class="form-select-topo">
                    <option value="">Tous</option>
                    <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="termine"  {{ request('statut') === 'termine'  ? 'selected' : '' }}>Terminé</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label-topo">Utilisateur</label>
                <select name="user_id" class="form-select-topo">
                    <option value="">Tous</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-orange" style="flex:1;justify-content:center;">
                    <i class="bi bi-search"></i> Filtrer
                </button>
                @if(request()->anyFilled(['search','type','statut','user_id']))
                    <a href="{{ route('admin.dossiers.index') }}" class="btn-outline-orange" style="padding:9px 12px;">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- TABLEAU --}}
<div class="card-topo">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <h6 style="font-size:15px;font-weight:700;margin:0;color:var(--white);">
            <i class="bi bi-table" style="color:#f97316;margin-right:6px;"></i>
            {{ $dossiers->total() }} dossier(s) trouvé(s)
        </h6>
        <span style="font-size:12px;color:var(--text-muted);">
            Page {{ $dossiers->currentPage() }} / {{ $dossiers->lastPage() }}
        </span>
    </div>

    @if($dossiers->isEmpty())
        <div style="text-align:center;padding:48px;color:var(--text-muted);">
            <i class="bi bi-folder-x" style="font-size:44px;opacity:0.3;display:block;margin-bottom:12px;"></i>
            Aucun dossier ne correspond aux critères.
        </div>
    @else
        <div class="table-responsive">
            <table class="table-topo">
                <thead>
                    <tr>
                        <th>Réf.</th>
                        <th>Propriétaire</th>
                        <th>Type</th>
                        <th>Topographe</th>
                        <th>Statut</th>
                        <th>Docs</th>
                        <th>Date</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dossiers as $dossier)
                    <tr>
                        <td>
                            <span style="font-weight:700;color:#60a5fa;font-size:12px;">
                                #{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight:600;color:var(--white);">{{ $dossier->proprietaire }}</div>
                            @if($dossier->localisation)
                                <div style="font-size:11px;color:var(--text-muted);">
                                    <i class="bi bi-geo-alt"></i> {{ $dossier->localisation }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge-topo badge-info" style="font-size:10px;">
                                {{ $dossier->type_label }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:28px;height:28px;background:rgba(59,130,246,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#60a5fa;font-weight:700;font-size:11px;border:1px solid rgba(59,130,246,0.3);">
                                    {{ strtoupper(substr($dossier->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-size:12.5px;font-weight:500;color:var(--white);">
                                        {{ $dossier->user->name ?? '—' }}
                                    </div>
                                    @if($dossier->user?->isAdmin())
                                        <div style="font-size:10px;color:#fbbf24;">Admin</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($dossier->statut === 'termine')
                                <span class="badge-topo badge-success">Terminé</span>
                            @else
                                <span class="badge-topo badge-warning">En cours</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-weight:700;color:var(--white);">{{ $dossier->documents_count }}</span>
                            <span style="font-size:10px;color:var(--text-muted);"> doc(s)</span>
                        </td>
                        <td style="font-size:11.5px;color:var(--text-muted);">
                            {{ $dossier->date_creation->format('d/m/Y') }}
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;justify-content:flex-end;gap:6px;">
                                <a href="{{ route('admin.dossiers.show', $dossier) }}"
                                   style="padding:5px 9px;background:rgba(59,130,246,0.12);color:#60a5fa;border:1px solid rgba(59,130,246,0.25);border-radius:7px;font-size:12px;text-decoration:none;"
                                   title="Voir le détail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.dossiers.edit', $dossier) }}" class="btn-outline-orange"
                                   style="padding:5px 9px;font-size:12px;" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.dossiers.destroy', $dossier) }}" method="POST"
                                      onsubmit="return confirm('Supprimer le dossier #{{ $dossier->id }} de {{ $dossier->proprietaire }} ? Cette action est irréversible.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="padding:5px 9px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;border-radius:7px;font-size:12px;cursor:pointer;"
                                            title="Supprimer">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;">
            {{ $dossiers->links() }}
        </div>
    @endif
</div>
@endsection
