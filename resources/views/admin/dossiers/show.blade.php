@extends('layouts.app')

@section('title', 'Admin — Dossier #' . str_pad($dossier->id, 5, '0', STR_PAD_LEFT))
@section('page-title', 'Détail dossier (Admin)')

@section('content')
<div class="breadcrumb-topo mb-4">
    <a href="{{ route('dashboard') }}">Accueil</a>
    <span class="sep">/</span>
    <a href="{{ route('admin.users.index') }}">Administration</a>
    <span class="sep">/</span>
    <a href="{{ route('admin.dossiers.index') }}">Dossiers</a>
    <span class="sep">/</span>
    <span>#{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }}</span>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        {{-- EN-TÊTE DOSSIER --}}
        <div class="card-topo mb-4">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:20px;padding-bottom:18px;border-bottom:1px solid var(--border);">
                <div>
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                        <span class="badge-topo badge-{{ $dossier->statut_badge }}">{{ $dossier->statut_label }}</span>
                        <span class="badge-topo badge-info">{{ $dossier->type_label }}</span>
                        <span style="background:rgba(249,115,22,0.12);color:#f97316;border:1px solid rgba(249,115,22,0.3);border-radius:6px;font-size:10px;padding:2px 8px;font-weight:700;">ADMIN</span>
                    </div>
                    <h4 style="font-size:22px;font-weight:800;margin:0;color:var(--white);">
                        {{ $dossier->proprietaire }}
                    </h4>
                    @if($dossier->localisation)
                        <p style="color:var(--text-muted);font-size:13px;margin:6px 0 0;">
                            <i class="bi bi-geo-alt-fill" style="color:#60a5fa;"></i> {{ $dossier->localisation }}
                        </p>
                    @endif
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <a href="{{ route('admin.dossiers.edit', $dossier) }}" class="btn-outline-orange">
                        <i class="bi bi-pencil-square"></i> Modifier
                    </a>
                    <form method="POST" action="{{ route('admin.dossiers.destroy', $dossier) }}"
                          onsubmit="return confirm('Supprimer définitivement ce dossier ?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="padding:7px 12px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;border-radius:8px;font-size:13px;cursor:pointer;">
                            <i class="bi bi-trash3"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-3">
                    <div style="background:var(--card-bg);border-radius:10px;padding:14px 16px;">
                        <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:4px;">Référence</div>
                        <div style="font-size:14px;font-weight:700;color:#60a5fa;">#{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div style="background:var(--card-bg);border-radius:10px;padding:14px 16px;">
                        <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:4px;">Date création</div>
                        <div style="font-size:14px;font-weight:600;color:var(--white);">{{ $dossier->date_creation->format('d/m/Y') }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div style="background:var(--card-bg);border-radius:10px;padding:14px 16px;">
                        <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:4px;">Documents</div>
                        <div style="font-size:14px;font-weight:600;color:#60a5fa;">{{ $dossier->documents->count() }} fichier(s)</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div style="background:var(--card-bg);border-radius:10px;padding:14px 16px;">
                        <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:4px;">Topographe</div>
                        <div style="font-size:13px;font-weight:600;color:var(--white);">{{ $dossier->user->name ?? '—' }}</div>
                    </div>
                </div>
            </div>

            @if($dossier->description)
                <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
                    <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:8px;">Description</div>
                    <p style="font-size:14px;line-height:1.75;color:rgba(255,255,255,0.7);margin:0;">{{ $dossier->description }}</p>
                </div>
            @endif
        </div>

        {{-- DOCUMENTS --}}
        <div class="card-topo">
            <h6 style="font-size:15px;font-weight:700;margin-bottom:16px;">
                <i class="bi bi-files" style="color:#60a5fa;"></i> Documents ({{ $dossier->documents->count() }})
            </h6>
            @if($dossier->documents->isEmpty())
                <div style="text-align:center;padding:28px;color:var(--text-muted);border:1px dashed var(--border);border-radius:10px;">
                    <i class="bi bi-file-earmark-x" style="font-size:36px;opacity:0.3;display:block;margin-bottom:8px;"></i>
                    Aucun document
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:8px;">
                    @foreach($dossier->documents as $doc)
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:var(--card-bg);border:1px solid var(--border);border-radius:10px;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:36px;height:36px;background:rgba(59,130,246,0.12);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi {{ $doc->icon }}" style="font-size:18px;color:#60a5fa;"></i>
                            </div>
                            <div>
                                <div style="font-size:13.5px;font-weight:600;color:var(--white);">{{ $doc->nom }}</div>
                                <div style="font-size:11px;color:var(--text-muted);">{{ $doc->type_document }} &bull; {{ strtoupper($doc->extension ?? '?') }}</div>
                            </div>
                        </div>
                        <a href="{{ $doc->url }}" target="_blank"
                           style="padding:6px 12px;background:rgba(37,99,235,0.12);color:#60a5fa;border:1px solid rgba(59,130,246,0.25);border-radius:6px;font-size:12px;text-decoration:none;">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- COLONNE DROITE --}}
    <div class="col-lg-4">
        @php
            $items = $dossier->checklist;
            $uploadedTypes = $dossier->documents->pluck('type_document')->toArray();
            $doneCnt = 0;
            foreach($items as $item) { if(in_array($item, $uploadedTypes)) $doneCnt++; }
            $pct = count($items) > 0 ? round(($doneCnt / count($items)) * 100) : 0;
        @endphp

        {{-- PROGRESSION --}}
        <div class="card-topo mb-4">
            <h6 style="font-size:13px;font-weight:700;margin-bottom:14px;color:var(--white);">Progression</h6>
            <div style="height:6px;background:var(--card-bg);border-radius:3px;overflow:hidden;">
                <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,#3b82f6,#2563eb);border-radius:3px;"></div>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--text-muted);margin-top:8px;">
                <span>{{ $doneCnt }}/{{ count($items) }} docs</span>
                <span style="color:var(--white);font-weight:700;">{{ $pct }}%</span>
            </div>
        </div>

        {{-- CHECKLIST --}}
        @if(count($items) > 0)
        <div class="card-topo mb-4">
            <h6 style="font-size:13px;font-weight:700;margin-bottom:12px;color:var(--white);">
                <i class="bi bi-list-check" style="color:#60a5fa;"></i> Checklist ANCFCC
            </h6>
            <div style="display:flex;flex-direction:column;gap:6px;">
                @foreach($items as $item)
                    @php $isDone = in_array($item, $uploadedTypes); @endphp
                    <div style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:var(--card-bg);border:1px solid {{ $isDone ? 'rgba(34,197,94,0.25)' : 'var(--border)' }};border-radius:8px;">
                        <div style="width:20px;height:20px;border-radius:50%;border:2px solid {{ $isDone ? '#22c55e' : 'rgba(255,255,255,0.15)' }};background:{{ $isDone ? 'rgba(34,197,94,0.2)' : 'transparent' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            @if($isDone)<i class="bi bi-check" style="color:#4ade80;font-size:11px;"></i>@endif
                        </div>
                        <span style="font-size:12px;color:{{ $isDone ? 'rgba(255,255,255,0.8)' : 'var(--text-muted)' }};">{{ $item }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ACTIONS ADMIN --}}
        <div class="card-topo">
            <h6 style="font-size:13px;font-weight:700;margin-bottom:14px;color:#f97316;">
                <i class="bi bi-shield-lock"></i> Actions Admin
            </h6>
            <div style="display:flex;flex-direction:column;gap:8px;">
                <a href="{{ route('admin.dossiers.edit', $dossier) }}" class="btn-orange" style="justify-content:center;">
                    <i class="bi bi-pencil-square"></i> Modifier ce dossier
                </a>
                <a href="{{ route('pdf.rapport', $dossier) }}" class="btn-outline-orange" style="justify-content:center;" target="_blank">
                    <i class="bi bi-file-earmark-text"></i> Rapport Technique
                </a>
                <a href="{{ route('pdf.pv', $dossier) }}" class="btn-outline-orange" style="justify-content:center;" target="_blank">
                    <i class="bi bi-clipboard-check"></i> PV de Bornage
                </a>
                <a href="{{ route('pdf.fiche', $dossier) }}" class="btn-outline-orange" style="justify-content:center;" target="_blank">
                    <i class="bi bi-card-list"></i> Fiche Dossier
                </a>
                <form method="POST" action="{{ route('admin.dossiers.destroy', $dossier) }}"
                      onsubmit="return confirm('Supprimer définitivement ce dossier et ses documents ?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="width:100%;padding:9px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;border-radius:8px;font-size:13px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
                        <i class="bi bi-trash3"></i> Supprimer définitivement
                    </button>
                </form>
                <a href="{{ route('admin.dossiers.index') }}" style="display:flex;align-items:center;justify-content:center;gap:6px;padding:9px;color:var(--text-muted);text-decoration:none;font-size:13px;">
                    <i class="bi bi-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
