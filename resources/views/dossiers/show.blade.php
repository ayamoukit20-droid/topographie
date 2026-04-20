@extends('layouts.app')

@section('title', 'Dossier — ' . $dossier->proprietaire)
@section('page-title', 'Detail du dossier')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
.checklist-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-radius: 8px;
    background: var(--card-bg);
    border: 1px solid var(--border);
    font-size: 13px;
    transition: all 0.2s;
}
.checklist-item.done {
    border-color: rgba(34,197,94,0.25);
    background: rgba(34,197,94,0.05);
}
.check-circle {
    width: 22px; height: 22px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.15);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 11px;
    transition: all 0.2s;
}
.check-circle.done {
    background: rgba(34,197,94,0.2);
    border-color: #22c55e;
    color: #4ade80;
}
.progress-bar-topo {
    height: 6px;
    background: var(--card-bg);
    border-radius: 3px;
    overflow: hidden;
}
.progress-fill {
    height: 100%;
    border-radius: 3px;
    background: linear-gradient(90deg, var(--blue-400), var(--blue-300));
    transition: width 0.6s ease;
}
.doc-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 10px;
    transition: border-color 0.2s;
}
.doc-row:hover { border-color: rgba(59,130,246,0.25); }
.upload-zone {
    padding: 22px;
    background: var(--card-bg);
    border: 2px dashed rgba(255,255,255,0.1);
    border-radius: 12px;
    transition: border-color 0.2s;
}
.upload-zone:hover { border-color: rgba(59,130,246,0.3); }
.info-block {
    background: var(--card-bg);
    border-radius: 10px;
    padding: 14px 16px;
}
.info-block-label {
    font-size: 10px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 4px;
}
.info-block-val {
    font-size: 14px;
    font-weight: 600;
    color: var(--white);
}
</style>
@endpush

@section('content')
<div class="breadcrumb-topo">
    <a href="{{ route('dashboard') }}">Accueil</a>
    <span class="sep">/</span>
    <a href="{{ route('dossiers.index') }}">Dossiers</a>
    <span class="sep">/</span>
    <span>{{ $dossier->proprietaire }}</span>
</div>

<div class="row g-4">

    {{-- ═══ COLONNE GAUCHE ═══ --}}
    <div class="col-lg-8">

        {{-- ENTETE DOSSIER --}}
        <div class="card-topo mb-4">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:20px;padding-bottom:18px;border-bottom:1px solid var(--border);">
                <div>
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                        <span class="badge-topo badge-{{ $dossier->statut_badge }}">{{ $dossier->statut_label }}</span>
                        <span class="badge-topo badge-info">{{ $dossier->type_label }}</span>
                    </div>
                    <h4 style="font-size:22px;font-weight:800;margin:0;color:var(--white);">
                        {{ $dossier->proprietaire }}
                    </h4>
                    @if($dossier->localisation)
                        <p style="color:var(--text-muted);font-size:13px;margin:6px 0 0;display:flex;align-items:center;gap:5px;">
                            <i class="bi bi-geo-alt-fill" style="color:#60a5fa;"></i>
                            {{ $dossier->localisation }}
                        </p>
                    @endif
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <a href="{{ route('dossiers.edit', $dossier) }}" class="btn-outline-orange">
                        <i class="bi bi-pencil-square"></i> Modifier
                    </a>
                    <a href="{{ route('pdf.dossier', $dossier) }}" class="btn-orange" target="_blank" id="btn-pdf-dossier">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a>
                    <form method="POST" action="{{ route('dossiers.destroy', $dossier) }}"
                          onsubmit="return confirm('Supprimer definitivement ce dossier ?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="padding:7px 12px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;border-radius:8px;font-size:13px;cursor:pointer;">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Infos en grille --}}
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="info-block">
                        <div class="info-block-label">Date de creation</div>
                        <div class="info-block-val">{{ $dossier->date_creation->format('d/m/Y') }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-block">
                        <div class="info-block-label">Documents</div>
                        <div class="info-block-val" style="color:#60a5fa;">{{ $dossier->documents->count() }} fichier(s)</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-block">
                        <div class="info-block-label">Cree par</div>
                        <div class="info-block-val">{{ auth()->user()->name }}</div>
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
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <h6 style="font-size:15px;font-weight:700;margin:0;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-files" style="color:#60a5fa;"></i> Documents attaches
                </h6>
                <span style="font-size:12px;color:var(--text-muted);">{{ $dossier->documents->count() }} document(s)</span>
            </div>

            @if($dossier->documents->isEmpty())
                <div style="text-align:center;padding:32px;color:var(--text-muted);border:1px dashed var(--border);border-radius:10px;">
                    <i class="bi bi-file-earmark-x" style="font-size:40px;opacity:0.35;display:block;margin-bottom:10px;"></i>
                    <p style="font-size:13px;margin:0;">Aucun document pour ce dossier</p>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:20px;">
                    @foreach($dossier->documents as $doc)
                        <div class="doc-row">
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div style="width:38px;height:38px;background:rgba(59,130,246,0.12);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi {{ $doc->icon }}" style="font-size:18px;color:#60a5fa;"></i>
                                </div>
                                <div>
                                    <div style="font-size:13.5px;font-weight:600;color:var(--white);">{{ $doc->nom }}</div>
                                    <div style="font-size:11px;color:var(--text-muted);">
                                        {{ $doc->type_document }} &bull; {{ strtoupper($doc->extension ?? '?') }}
                                        @if($doc->taille) &bull; {{ $doc->taille }} @endif
                                    </div>
                                </div>
                            </div>
                            <div style="display:flex;gap:8px;">
                                <a href="{{ $doc->url }}" target="_blank"
                                   style="padding:6px 12px;background:rgba(37,99,235,0.12);color:#60a5fa;border:1px solid rgba(59,130,246,0.25);border-radius:6px;font-size:12px;text-decoration:none;display:flex;align-items:center;gap:4px;">
                                    <i class="bi bi-download"></i> Telecharger
                                </a>
                                <form method="POST" action="{{ route('documents.destroy', $doc) }}"
                                      onsubmit="return confirm('Supprimer ce document ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding:6px 10px;background:rgba(239,68,68,0.1);color:#f87171;border:1px solid rgba(239,68,68,0.25);border-radius:6px;font-size:12px;cursor:pointer;">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Upload zone --}}
            <div class="upload-zone" id="upload-zone">
                <h6 style="font-size:14px;font-weight:700;margin-bottom:16px;color:#60a5fa;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-cloud-upload"></i> Ajouter un document
                </h6>
                <form method="POST" action="{{ route('documents.store', $dossier) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label-topo">Nom du document *</label>
                            <input type="text" name="nom" class="form-control-topo"
                                   placeholder="Ex: PV de bornage" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-topo">Type *</label>
                            <select name="type_document" class="form-select-topo" required>
                                <option value="">-- Type --</option>
                                <option value="PV">PV (Proces-verbal)</option>
                                <option value="plan">Plan</option>
                                <option value="tableau">Tableau</option>
                                <option value="rapport">Rapport</option>
                                <option value="autorisation">Autorisation</option>
                                <option value="titre">Titre foncier</option>
                                <option value="certificat">Certificat</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div style="width:100%;">
                                <label class="form-label-topo">Fichier *</label>
                                <input type="file" name="fichier" class="form-control-topo"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip" required
                                       style="padding:8px 10px;font-size:12px;">
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn-orange" id="upload-submit-btn">
                                <i class="bi bi-upload"></i> Uploader
                            </button>
                            <span style="font-size:11px;color:var(--text-muted);margin-left:10px;">PDF, DOC, XLS, JPG, PNG &mdash; Max 10MB</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══ COLONNE DROITE ═══ --}}
    <div class="col-lg-4">

        {{-- PROGRESSION --}}
        <div class="card-topo mb-4">
            <h6 style="font-size:13px;font-weight:700;margin-bottom:14px;color:var(--white);">Progression</h6>
            @php
                $progress = match($dossier->statut) {
                    'en_cours' => 33,
                    'valide'   => 66,
                    'termine'  => 100,
                    default    => 0
                };
                $progressColor = match($dossier->statut) {
                    'en_cours' => 'linear-gradient(90deg,#f59e0b,#fbbf24)',
                    'valide'   => 'linear-gradient(90deg,#2563eb,#3b82f6)',
                    'termine'  => 'linear-gradient(90deg,#10b981,#34d399)',
                    default    => 'var(--blue-400)'
                };
            @endphp
            <div class="progress-bar-topo">
                <div class="progress-fill" style="width:{{ $progress }}%;background:{{ $progressColor }};"></div>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--text-muted);margin-top:8px;">
                <span>Creation</span>
                <span style="color:var(--white);font-weight:700;">{{ $progress }}%</span>
                <span>Termine</span>
            </div>
        </div>

        {{-- CHECKLIST ANCFCC --}}
        <div class="card-topo mb-4">
            <h6 style="font-size:13px;font-weight:700;margin-bottom:4px;color:var(--white);display:flex;align-items:center;gap:8px;">
                <i class="bi bi-list-check" style="color:#60a5fa;"></i> Checklist ANCFCC
            </h6>
            <p style="font-size:11px;color:var(--text-muted);margin-bottom:14px;">Documents requis pour : <strong style="color:#93c5fd;">{{ $dossier->type_label }}</strong></p>

            @php
                $checklists = [
                    'immatriculation' => [
                        'Requisition d\'immatriculation',
                        'PV de bornage',
                        'Plan de bornage',
                        'Plan de situation',
                        'Tableau de coordonnees (X,Y)',
                        'Calcul de contenance',
                    ],
                    'maj' => [
                        'Titre foncier',
                        'Autorisation de construire',
                        'Plan architecte',
                        'Permis d\'habiter',
                        'Plan de situation',
                        'Plan de mise a jour',
                        'Calcul surface batie',
                    ],
                    'copropriete' => [
                        'Titre foncier',
                        'Certificat de propriete',
                        'Autorisation de construire',
                        'Certificat de conformite',
                        'Note de renseignements',
                        'Plan de situation',
                        'Plan de masse',
                        'Plans architecturaux (RDC, etages)',
                        'Tableau des surfaces (Tableau A)',
                        'Tableau des tantiemes (Tableau B)',
                        'Reglement de copropriete',
                        'Etat descriptif de division',
                    ],
                    'morcellement' => [
                        'Note de renseignements',
                        'Autorisation de division',
                        'Plan de division',
                        'Calcul nouvelles surfaces',
                        'Plan cadastral',
                    ],
                    'lotissement' => [
                        'Plan de lotissement',
                        'Cahier des charges',
                        'Plan de masse',
                        'Plan voirie (VRD)',
                        'Plan reseaux',
                        'PV de reception',
                    ],
                ];
                $type = $dossier->type_dossier ?? 'immatriculation';
                $items = $checklists[$type] ?? $checklists['immatriculation'];
                $uploadedNames = $dossier->documents->pluck('nom')->toArray();
                $doneCnt = 0;
                foreach($items as $item) {
                    foreach($uploadedNames as $uname) {
                        if(stripos($uname, explode(' ', $item)[0]) !== false) { $doneCnt++; break; }
                    }
                }
                $pct = count($items) > 0 ? round(($doneCnt / count($items)) * 100) : 0;
            @endphp

            <div style="margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--text-muted);margin-bottom:6px;">
                    <span>{{ $doneCnt }}/{{ count($items) }} fournis</span>
                    <span style="color:#60a5fa;font-weight:700;">{{ $pct }}%</span>
                </div>
                <div class="progress-bar-topo">
                    <div class="progress-fill" style="width:{{ $pct }}%;background:linear-gradient(90deg,#10b981,#34d399);"></div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:6px;">
                @foreach($items as $item)
                    @php
                        $isDone = false;
                        $keyword = explode(' ', $item)[0];
                        foreach($uploadedNames as $uname) {
                            if(stripos($uname, $keyword) !== false) { $isDone = true; break; }
                        }
                    @endphp
                    <div class="checklist-item {{ $isDone ? 'done' : '' }}">
                        <div class="check-circle {{ $isDone ? 'done' : '' }}">
                            @if($isDone)
                                <i class="bi bi-check"></i>
                            @endif
                        </div>
                        <span style="font-size:12.5px;color:{{ $isDone ? 'rgba(255,255,255,0.75)' : 'var(--text-muted)' }};">{{ $item }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="card-topo">
            <h6 style="font-size:13px;font-weight:700;margin-bottom:14px;color:var(--white);">Actions rapides</h6>
            <div style="display:flex;flex-direction:column;gap:8px;">
                <a href="{{ route('dossiers.edit', $dossier) }}" class="btn-orange" style="justify-content:center;">
                    <i class="bi bi-pencil-square"></i> Modifier le dossier
                </a>
                <a href="{{ route('pdf.dossier', $dossier) }}" class="btn-outline-orange" style="justify-content:center;" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> Generer PDF
                </a>
                <a href="{{ route('outils.index') }}" class="btn-outline-orange" style="justify-content:center;">
                    <i class="bi bi-calculator"></i> Outils de calcul
                </a>
                <a href="{{ route('chatbot.index') }}" class="btn-outline-orange" style="justify-content:center;">
                    <i class="bi bi-chat-dots"></i> Poser une question
                </a>
                <a href="{{ route('dossiers.index') }}" style="display:flex;align-items:center;justify-content:center;gap:6px;padding:9px;color:var(--text-muted);text-decoration:none;font-size:13px;">
                    <i class="bi bi-arrow-left"></i> Retour a la liste
                </a>
            </div>
        </div>

        {{-- CARTE LEAFLET --}}
        @if($dossier->lat && $dossier->lng)
        <div class="card-topo mt-4">
            <h6 style="font-size:13px;font-weight:700;margin-bottom:10px;display:flex;align-items:center;gap:7px;">
                <i class="bi bi-geo-alt-fill" style="color:#60a5fa;"></i> Position GPS
            </h6>
            <p style="font-size:11px;color:var(--text-muted);margin-bottom:8px;">
                <i class="bi bi-pin-map"></i>
                Lat: {{ number_format($dossier->lat, 5) }} &nbsp;|&nbsp; Lng: {{ number_format($dossier->lng, 5) }}
            </p>
            <div id="map-show" style="height:220px;border-radius:10px;border:1px solid var(--border);overflow:hidden;"></div>
        </div>
        @else
        <div class="card-topo mt-4" style="text-align:center;padding:20px;">
            <i class="bi bi-geo-alt" style="font-size:32px;color:var(--text-muted);opacity:0.4;display:block;margin-bottom:8px;"></i>
            <p style="font-size:12px;color:var(--text-muted);margin:0;">Aucune position GPS enregistree.</p>
            <a href="{{ route('dossiers.edit', $dossier) }}" style="font-size:12px;color:#60a5fa;text-decoration:none;">
                Ajouter une position
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@if($dossier->lat && $dossier->lng)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lat = {{ $dossier->lat }};
    const lng = {{ $dossier->lng }};

    const map = L.map('map-show', {
        zoomControl: true,
        scrollWheelZoom: false,
        attributionControl: true
    }).setView([lat, lng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19
    }).addTo(map);

    const icon = L.divIcon({
        className: '',
        html: `<div style="width:28px;height:28px;background:#2563eb;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 3px 10px rgba(0,0,0,0.4);"></div>`,
        iconSize: [28, 28],
        iconAnchor: [14, 28],
        popupAnchor: [0, -30]
    });

    L.marker([lat, lng], { icon })
        .addTo(map)
        .bindPopup(`
            <div style="font-family:Inter,sans-serif;min-width:160px;">
                <strong style="font-size:13px;">{{ $dossier->proprietaire }}</strong><br>
                <span style="font-size:11px;color:#64748b;">{{ $dossier->type_label }}</span><br>
                @if($dossier->localisation)
                <span style="font-size:11px;color:#64748b;"><i>{{ $dossier->localisation }}</i></span>
                @endif
            </div>
        `)
        .openPopup();
});
</script>
@endif
@endpush
