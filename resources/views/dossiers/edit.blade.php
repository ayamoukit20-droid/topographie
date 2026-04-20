@extends('layouts.app')

@section('title', 'Modifier le dossier')
@section('page-title', 'Modifier le dossier')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
#map-edit {
    height: 260px;
    border-radius: 10px;
    border: 1.5px solid var(--border);
    margin-top: 8px;
}
#map-edit.has-location { border-color: rgba(34,197,94,0.4); }
.coords-badge {
    display: none;
    align-items: center;
    gap: 8px;
    padding: 7px 12px;
    background: rgba(34,197,94,0.1);
    border: 1px solid rgba(34,197,94,0.25);
    border-radius: 8px;
    font-size: 12px;
    color: #4ade80;
    margin-top: 6px;
}
</style>
@endpush

@section('content')
<div class="breadcrumb-topo">
    <a href="{{ route('dashboard') }}">Accueil</a>
    <span class="sep">/</span>
    <a href="{{ route('dossiers.index') }}">Dossiers</a>
    <span class="sep">/</span>
    <a href="{{ route('dossiers.show', $dossier) }}">{{ $dossier->proprietaire }}</a>
    <span class="sep">/</span>
    <span>Modifier</span>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        {{-- FORMULAIRE PRINCIPAL --}}
        <div class="card-topo mb-4">
            <h6 style="font-size:16px;font-weight:700;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid var(--border);">
                <i class="bi bi-pencil-square" style="color:#60a5fa;margin-right:8px;"></i>
                Modifier &mdash; <span style="color:var(--text-muted);">{{ $dossier->proprietaire }}</span>
            </h6>

            <form method="POST" action="{{ route('dossiers.update', $dossier) }}" id="edit-form">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-topo">Type de dossier *</label>
                        <select name="type_dossier" class="form-select-topo" required>
                            <option value="immatriculation" {{ old('type_dossier',$dossier->type_dossier)=='immatriculation' ?'selected':'' }}>Immatriculation Fonciere</option>
                            <option value="maj"             {{ old('type_dossier',$dossier->type_dossier)=='maj'             ?'selected':'' }}>Mise a Jour (MAJ)</option>
                            <option value="copropriete"     {{ old('type_dossier',$dossier->type_dossier)=='copropriete'     ?'selected':'' }}>Copropriete</option>
                            <option value="morcellement"    {{ old('type_dossier',$dossier->type_dossier)=='morcellement'    ?'selected':'' }}>Morcellement</option>
                            <option value="lotissement"     {{ old('type_dossier',$dossier->type_dossier)=='lotissement'     ?'selected':'' }}>Lotissement</option>
                        </select>
                        @error('type_dossier')
                            <div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-topo">Statut *</label>
                        <select name="statut" class="form-select-topo" required>
                            <option value="en_cours" {{ old('statut',$dossier->statut)=='en_cours'?'selected':'' }}>En cours</option>
                            <option value="valide"   {{ old('statut',$dossier->statut)=='valide'  ?'selected':'' }}>Valide</option>
                            <option value="termine"  {{ old('statut',$dossier->statut)=='termine' ?'selected':'' }}>Termine</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label-topo">Proprietaire *</label>
                        <input type="text" name="proprietaire"
                               value="{{ old('proprietaire', $dossier->proprietaire) }}"
                               class="form-control-topo" required>
                        @error('proprietaire')
                            <div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-topo">Date de creation *</label>
                        <input type="date" name="date_creation"
                               value="{{ old('date_creation', $dossier->date_creation->format('Y-m-d')) }}"
                               class="form-control-topo" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-topo">Localisation</label>
                        <input type="text" name="localisation"
                               value="{{ old('localisation', $dossier->localisation) }}"
                               class="form-control-topo" placeholder="Commune, douar, wilaya...">
                    </div>

                    <div class="col-12">
                        <label class="form-label-topo">Description</label>
                        <textarea name="description" rows="3" class="form-control-topo">{{ old('description', $dossier->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-3">
                    <button type="submit" class="btn-orange">
                        <i class="bi bi-save2"></i> Sauvegarder
                    </button>
                    <a href="{{ route('dossiers.show', $dossier) }}" class="btn-outline-orange">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>
            </form>
        </div>

        {{-- CARTE LEAFLET --}}
        <div class="card-topo">
            <h6 style="font-size:15px;font-weight:700;margin-bottom:8px;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-geo-alt-fill" style="color:#60a5fa;"></i>
                Modifier la position GPS
                <span style="font-size:11px;font-weight:400;color:var(--text-muted);margin-left:4px;">(Cliquez pour repositionner)</span>
            </h6>
            <p style="font-size:12px;color:var(--text-muted);margin-bottom:8px;">
                Cliquez sur la carte ou faites glisser le marqueur pour mettre a jour la position.
            </p>
            <div id="map-edit"></div>
            <div class="coords-badge" id="coords-badge-edit">
                <i class="bi bi-pin-map-fill"></i>
                <span id="coords-text-edit">Position selectionnee</span>
            </div>
            {{-- Les champs lat/lng sont dans le form principal via JS --}}
        </div>

    </div>

    <div class="col-lg-4">
        {{-- INFOS DU DOSSIER --}}
        <div class="card-topo mb-4">
            <h6 style="font-size:14px;font-weight:700;margin-bottom:16px;display:flex;align-items:center;gap:7px;">
                <i class="bi bi-info-circle" style="color:#60a5fa;"></i> Infos du dossier
            </h6>
            <div style="display:flex;flex-direction:column;gap:10px;font-size:13px;color:var(--text-muted);">
                <div style="display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-hash" style="color:#60a5fa;"></i>
                    <span>Reference : <strong style="color:var(--white);">#{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }}</strong></span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-calendar" style="color:#60a5fa;"></i>
                    <span>Cree le : <strong style="color:var(--white);">{{ $dossier->created_at->format('d/m/Y H:i') }}</strong></span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-files" style="color:#60a5fa;"></i>
                    <span>Documents : <strong style="color:var(--white);">{{ $dossier->documents->count() }}</strong></span>
                </div>
                @if($dossier->lat && $dossier->lng)
                <div style="display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-geo-alt" style="color:#22c55e;"></i>
                    <span style="color:#4ade80;">Position GPS enregistree</span>
                </div>
                @endif
            </div>
        </div>

        {{-- ACTIONS RAPIDES --}}
        <div class="card-topo mb-4">
            <h6 style="font-size:14px;font-weight:700;margin-bottom:14px;">Actions rapides</h6>
            <div style="display:flex;flex-direction:column;gap:8px;">
                <a href="{{ route('dossiers.show', $dossier) }}" class="btn-outline-orange" style="justify-content:center;">
                    <i class="bi bi-eye"></i> Voir le dossier
                </a>
                <a href="{{ route('pdf.dossier', $dossier) }}" class="btn-outline-orange" style="justify-content:center;" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> Generer PDF
                </a>
            </div>
        </div>

        {{-- SUPPRIMER --}}
        <div class="card-topo" style="border-left:3px solid rgba(239,68,68,0.4);">
            <h6 style="font-size:13px;font-weight:700;color:#f87171;margin-bottom:12px;">
                <i class="bi bi-exclamation-triangle"></i> Zone dangereuse
            </h6>
            <p style="font-size:12px;color:var(--text-muted);margin-bottom:12px;line-height:1.6;">
                La suppression est irreversible. Tous les documents associes seront perdus.
            </p>
            <form method="POST" action="{{ route('dossiers.destroy', $dossier) }}"
                  onsubmit="return confirm('Supprimer definitivement ce dossier et tous ses documents ?')">
                @csrf @method('DELETE')
                <button type="submit" style="width:100%;padding:9px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.4);color:#f87171;border-radius:8px;font-size:13px;cursor:pointer;transition:all 0.2s;">
                    <i class="bi bi-trash3"></i> Supprimer ce dossier
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// ── Ajouter les champs lat/lng dans le form principal
const form = document.getElementById('edit-form');
const latInput = document.createElement('input');
const lngInput = document.createElement('input');
latInput.type = 'hidden'; latInput.name = 'lat'; latInput.id = 'lat-edit';
lngInput.type = 'hidden'; lngInput.name = 'lng'; lngInput.id = 'lng-edit';
latInput.value = '{{ $dossier->lat ?? "" }}';
lngInput.value = '{{ $dossier->lng ?? "" }}';
form.appendChild(latInput);
form.appendChild(lngInput);

// ── Carte
const savedLat = {{ $dossier->lat ?? 31.7917 }};
const savedLng = {{ $dossier->lng ?? -7.0926 }};
const defaultZoom = {{ $dossier->lat ? 14 : 6 }};

const map = L.map('map-edit', { zoomControl: true }).setView([savedLat, savedLng], defaultZoom);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors', maxZoom: 19
}).addTo(map);

let marker = null;

function makeIcon() {
    return L.divIcon({
        className: '',
        html: `<div style="width:28px;height:28px;background:#2563eb;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.4);"></div>`,
        iconSize: [28, 28], iconAnchor: [14, 28]
    });
}

function placeMarker(lat, lng) {
    if (marker) map.removeLayer(marker);
    marker = L.marker([lat, lng], { draggable: true, icon: makeIcon() }).addTo(map);
    updateCoords(lat, lng);
    marker.on('dragend', e => {
        const pos = e.target.getLatLng();
        updateCoords(pos.lat, pos.lng);
    });
    document.getElementById('map-edit').classList.add('has-location');
}

function updateCoords(lat, lng) {
    document.getElementById('lat-edit').value = lat.toFixed(7);
    document.getElementById('lng-edit').value = lng.toFixed(7);
    document.getElementById('coords-text-edit').textContent = `Lat: ${lat.toFixed(5)}, Lng: ${lng.toFixed(5)}`;
    document.getElementById('coords-badge-edit').style.display = 'flex';
}

// Charger position existante
@if($dossier->lat && $dossier->lng)
    placeMarker(savedLat, savedLng);
@endif

map.on('click', e => placeMarker(e.latlng.lat, e.latlng.lng));
</script>
@endpush
