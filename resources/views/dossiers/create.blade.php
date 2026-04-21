@extends('layouts.app')

@section('title', 'Nouveau dossier')
@section('page-title', 'Creer un dossier')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
#map-picker {
    height: 320px;
    border-radius: 10px;
    border: 1.5px solid var(--border);
    margin-top: 8px;
    z-index: 1;
}
#map-picker.has-location { border-color: rgba(34,197,94,0.4); }
.map-coords-badge {
    display: none;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    background: rgba(34,197,94,0.1);
    border: 1px solid rgba(34,197,94,0.25);
    border-radius: 8px;
    font-size: 13px;
    color: #4ade80;
    margin-top: 8px;
}
.leaflet-container { background: #071e3d !important; }
</style>
@endpush

@section('content')
<div class="breadcrumb-topo">
    <a href="{{ route('dashboard') }}">Accueil</a>
    <span class="sep">/</span>
    <a href="{{ route('dossiers.index') }}">Dossiers</a>
    <span class="sep">/</span>
    <span>Nouveau</span>
</div>

<div class="row g-4">
    {{-- FORMULAIRE --}}
    <div class="col-lg-8">
        <form method="POST" action="{{ route('dossiers.store') }}" id="dossier-form">
            @csrf

            {{-- ── INFOS GENERALES ── --}}
            <div class="card-topo mb-4">
                <h6 style="font-size:15px;font-weight:700;margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-folder-plus" style="color:#60a5fa;"></i>
                    Informations du dossier
                </h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-topo">Type de dossier *</label>
                        <select name="type_dossier_id" class="form-select-topo" id="type-dossier-select" required>
                            <option value="">-- Selectionnez le type --</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" data-code="{{ $type->code }}" {{ old('type_dossier_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('type_dossier')
                            <div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-topo">Statut *</label>
                        <select name="statut" class="form-select-topo" required>
                            <option value="en_cours" {{ old('statut','en_cours')=='en_cours' ?'selected':'' }}>En cours</option>
                            <option value="termine"  {{ old('statut')=='termine'  ?'selected':'' }}>Termine</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label-topo">Proprietaire *</label>
                        <input type="text" name="proprietaire" value="{{ old('proprietaire') }}"
                               class="form-control-topo" placeholder="Nom complet du proprietaire" required>
                        @error('proprietaire')
                            <div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-topo">Date de creation *</label>
                        <input type="date" name="date_creation" value="{{ old('date_creation', date('Y-m-d')) }}"
                               class="form-control-topo" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-topo">Localisation</label>
                        <input type="text" name="localisation" value="{{ old('localisation') }}" id="localisation-input"
                               class="form-control-topo" placeholder="Commune, douar, province...">
                    </div>

                    <div class="col-12">
                        <label class="form-label-topo">Description</label>
                        <textarea name="description" rows="3" class="form-control-topo"
                                  placeholder="Description technique du dossier...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ── CARTE LEAFLET ── --}}
            <div class="card-topo mb-4">
                <h6 style="font-size:15px;font-weight:700;margin-bottom:8px;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-geo-alt-fill" style="color:#60a5fa;"></i>
                    Position sur la carte
                    <span style="font-size:11px;font-weight:400;color:var(--text-muted);margin-left:4px;">(Cliquez pour selectionner)</span>
                </h6>
                <p style="font-size:12px;color:var(--text-muted);margin-bottom:10px;">
                    Cliquez sur la carte pour placer le marqueur du dossier. Les coordonnees GPS seront enregistrees automatiquement.
                </p>

                <div id="map-picker"></div>

                <div class="map-coords-badge" id="coords-badge">
                    <i class="bi bi-pin-map-fill"></i>
                    <span id="coords-text">Position selectionnee</span>
                </div>

                {{-- Champs caches lat/lng --}}
                <input type="hidden" name="lat" id="lat-input" value="{{ old('lat') }}">
                <input type="hidden" name="lng" id="lng-input" value="{{ old('lng') }}">
            </div>

            {{-- ── BOUTONS ── --}}
            <div class="d-flex gap-3">
                <button type="submit" class="btn-orange" id="submit-btn">
                    <i class="bi bi-save2"></i> Enregistrer le dossier
                </button>
                <a href="{{ route('dossiers.index') }}" class="btn-outline-orange">
                    <i class="bi bi-x-lg"></i> Annuler
                </a>
            </div>
        </form>
    </div>

    {{-- PANNEAU LATERAL --}}
    <div class="col-lg-4">
        {{-- Guide interactif --}}
        <div class="card-topo mb-4" style="border-left:3px solid var(--blue-400);">
            <h6 style="font-size:13px;font-weight:700;color:#60a5fa;margin-bottom:14px;">
                <i class="bi bi-lightbulb-fill"></i> Guide de creation
            </h6>
            <div style="display:flex;flex-direction:column;gap:10px;">
                @php
                $steps = [
                    ['icon'=>'bi-tag', 'title'=>'Choisir le type', 'desc'=>'Selectionnez le type ANCFCC'],
                    ['icon'=>'bi-person', 'title'=>'Proprietaire', 'desc'=>'Nom complet du proprietaire'],
                    ['icon'=>'bi-calendar', 'title'=>'Date & Lieu', 'desc'=>'Date de debut et localisation'],
                    ['icon'=>'bi-geo-alt', 'title'=>'Carte', 'desc'=>'Cliquez pour positionner le dossier'],
                ];
                @endphp
                @foreach($steps as $i => $step)
                <div class="guide-step" data-step="{{ $i+1 }}" style="display:flex;gap:10px;opacity:{{ $i === 0 ? '1' : '0.45' }};transition:opacity 0.3s;">
                    <div style="width:28px;height:28px;background:rgba(37,99,235,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi {{ $step['icon'] }}" style="font-size:12px;color:#60a5fa;"></i>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:600;color:var(--white);">{{ $step['title'] }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $step['desc'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Checklist dynamique --}}
        <div class="card-topo" id="checklist-preview" style="display:none;">
            <h6 style="font-size:13px;font-weight:700;color:#60a5fa;margin-bottom:12px;display:flex;align-items:center;gap:7px;">
                <i class="bi bi-list-check"></i> Documents requis
            </h6>
            <p style="font-size:11px;color:var(--text-muted);margin-bottom:12px;">
                A fournir apres la creation :
            </p>
            <div id="checklist-items" style="display:flex;flex-direction:column;gap:6px;"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// ── CARTE LEAFLET ──
const defaultCenter = [31.7917, -7.0926]; // Centre Maroc
const map = L.map('map-picker', { zoomControl: true }).setView(defaultCenter, 6);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
}).addTo(map);

let marker = null;

// Si valeur precedente (old())
const oldLat = document.getElementById('lat-input').value;
const oldLng = document.getElementById('lng-input').value;
if (oldLat && oldLng) {
    placeMarker(parseFloat(oldLat), parseFloat(oldLng));
    map.setView([parseFloat(oldLat), parseFloat(oldLng)], 13);
}

map.on('click', function(e) {
    placeMarker(e.latlng.lat, e.latlng.lng);
});

function placeMarker(lat, lng) {
    if (marker) map.removeLayer(marker);
    marker = L.marker([lat, lng], {
        draggable: true,
        icon: L.divIcon({
            className: '',
            html: `<div style="width:32px;height:32px;background:#2563eb;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.4);"></div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 32]
        })
    }).addTo(map);

    updateCoords(lat, lng);

    marker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        updateCoords(pos.lat, pos.lng);
    });

    document.getElementById('map-picker').classList.add('has-location');
}

function updateCoords(lat, lng) {
    document.getElementById('lat-input').value = lat.toFixed(7);
    document.getElementById('lng-input').value = lng.toFixed(7);
    document.getElementById('coords-text').textContent =
        `Lat: ${lat.toFixed(5)}, Lng: ${lng.toFixed(5)}`;
    document.getElementById('coords-badge').style.display = 'flex';
}

// ── GUIDE INTERACTIF ──
const focusMap = {
    'select[name="type_dossier"]': 1,
    'input[name="proprietaire"]':   2,
    'input[name="date_creation"]':  3,
    '#map-picker':                  4,
};
Object.entries(focusMap).forEach(([sel, step]) => {
    const el = document.querySelector(sel);
    if (!el) return;
    el.addEventListener('focus', () => {
        document.querySelectorAll('.guide-step').forEach((s, i) => {
            s.style.opacity = (i + 1 === step) ? '1' : '0.35';
        });
    });
});

// ── CHECKLIST DYNAMIQUE ──
const CHECKLISTS = {
    @foreach($types as $type)
        "{{ $type->id }}": {!! json_encode($type->documentsRequis->pluck('nom')) !!},
    @endforeach
};

document.getElementById('type-dossier-select')?.addEventListener('change', function() {
    const items = CHECKLISTS[this.value];
    const preview = document.getElementById('checklist-preview');
    const container = document.getElementById('checklist-items');
    if (!items) { preview.style.display = 'none'; return; }
    container.innerHTML = '';
    items.forEach(item => {
        const div = document.createElement('div');
        div.style.cssText = 'display:flex;align-items:center;gap:9px;padding:7px 11px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:7px;';
        div.innerHTML = `<div style="width:16px;height:16px;border-radius:50%;border:1.5px solid rgba(255,255,255,0.2);flex-shrink:0;"></div><span style="font-size:12px;color:rgba(255,255,255,0.5);">${item}</span>`;
        container.appendChild(div);
    });
    preview.style.display = 'block';
});

// Trigger si old() value
const preselected = document.getElementById('type-dossier-select').value;
if (preselected) document.getElementById('type-dossier-select').dispatchEvent(new Event('change'));
</script>
@endpush
