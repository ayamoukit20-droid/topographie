@extends('layouts.app')

@section('title', 'Mes Dossiers')
@section('page-title', 'Gestion des dossiers')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-topo">
    <a href="{{ route('dashboard') }}">Accueil</a>
    <span class="sep">/</span>
    <span>Dossiers</span>
</div>

<!-- Filtres -->
<div class="card-topo mb-4">
    <form method="GET" action="{{ route('dossiers.index') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label-topo">Rechercher</label>
            <div style="position:relative;">
                <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control-topo" placeholder="Propriétaire, description..."
                       style="padding-left:36px;">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label-topo">Type de dossier</label>
            <select name="type" class="form-select-topo">
                <option value="">Tous les types</option>
                <option value="immatriculation" {{ request('type')=='immatriculation' ?'selected':'' }}>Immatriculation Fonciere</option>
                <option value="maj"             {{ request('type')=='maj'             ?'selected':'' }}>Mise a Jour (MAJ)</option>
                <option value="copropriete"     {{ request('type')=='copropriete'     ?'selected':'' }}>Copropriete</option>
                <option value="morcellement"    {{ request('type')=='morcellement'    ?'selected':'' }}>Morcellement</option>
                <option value="lotissement"     {{ request('type')=='lotissement'     ?'selected':'' }}>Lotissement</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label-topo">Statut</label>
            <select name="statut" class="form-select-topo">
                <option value="">Tous les statuts</option>
                <option value="en_cours" {{ request('statut')=='en_cours'?'selected':'' }}>En cours</option>
                <option value="termine"  {{ request('statut')=='termine' ?'selected':'' }}>Termine</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn-orange w-100">
                <i class="bi bi-funnel"></i> Filtrer
            </button>
            <a href="{{ route('dossiers.index') }}" class="btn-outline-orange" style="padding:8px 12px;">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>
    </form>
</div>

<!-- Liste dossiers -->
<div class="card-topo">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <h6 style="font-size:16px;font-weight:700;margin:0;">
            {{ $dossiers->total() }} dossier(s)
        </h6>
        <a href="{{ route('dossiers.create') }}" class="btn-orange">
            <i class="bi bi-plus-lg"></i> Nouveau dossier
        </a>
    </div>

    @if($dossiers->isEmpty())
        <div class="text-center py-5" style="color:var(--text-muted);">
            <i class="bi bi-folder-x" style="font-size:56px;opacity:0.3;"></i>
            <p class="mt-3 mb-0">Aucun dossier trouvé.</p>
            @if(request()->hasAny(['type','statut','search']))
                <p style="font-size:13px;">Essayez de modifier vos filtres.</p>
            @else
                <a href="{{ route('dossiers.create') }}" class="btn-orange mt-3">Créer un dossier</a>
            @endif
        </div>
    @else
        <table class="table-topo">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Propriétaire</th>
                    <th>Type</th>
                    <th>Localisation</th>
                    <th>Date</th>
                    <th>Documents</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($dossiers as $dossier)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">#{{ $dossier->id }}</td>
                    <td>
                        <strong>{{ $dossier->proprietaire }}</strong>
                        @if($dossier->description)
                            <div style="font-size:11px;color:var(--text-muted);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ $dossier->description }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="badge-topo badge-secondary">{{ $dossier->type_label }}</span>
                    </td>
                    <td style="color:var(--text-muted);font-size:13px;">
                        {{ $dossier->localisation ?? '—' }}
                    </td>
                    <td style="color:var(--text-muted);font-size:13px;">
                        {{ $dossier->date_creation->format('d/m/Y') }}
                    </td>
                    <td>
                        <span style="font-size:13px;">
                            <i class="bi bi-file-earmark" style="color:var(--orange);"></i>
                            {{ $dossier->documents_count ?? $dossier->documents->count() }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-topo badge-{{ $dossier->statut_badge }}">
                            {{ $dossier->statut_label }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:8px;align-items:center;">
                            <a href="{{ route('dossiers.show', $dossier) }}"
                               style="color:var(--orange);font-size:17px;" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('dossiers.edit', $dossier) }}"
                               style="color:#3b82f6;font-size:17px;" title="Modifier">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form method="POST" action="{{ route('dossiers.destroy', $dossier) }}"
                                  onsubmit="return confirm('Supprimer ce dossier et tous ses documents ?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:none;border:none;color:#ef4444;font-size:17px;cursor:pointer;" title="Supprimer">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        @if($dossiers->hasPages())
            <div class="mt-4 d-flex justify-content-center" style="gap:6px;">
                {{ $dossiers->withQueryString()->links('vendor.pagination.topo') }}
            </div>
        @endif
    @endif
</div>
@endsection
