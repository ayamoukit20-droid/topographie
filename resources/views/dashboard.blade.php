@extends('layouts.app')

@section('title', $isAdmin ? 'Tableau de bord — Administration' : 'Tableau de bord')
@section('page-title', $isAdmin ? 'Vue d\'ensemble du site' : 'Tableau de bord')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
.stats-grid-admin { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
.stats-grid-user  { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 28px; }
.chart-wrapper    { position: relative; height: 240px; width: 100%; }
.section-title    { font-size: 15px; font-weight: 700; color: var(--text-light); margin-bottom: 16px; display:flex;align-items:center;gap:8px; }
.quick-actions    { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
.quick-btn {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 16px; background: var(--card-bg);
    border: 1px solid var(--border); border-radius: 10px;
    color: var(--text-light); text-decoration: none;
    font-size: 13px; font-weight: 500; transition: all 0.2s;
}
.quick-btn:hover { border-color: var(--blue-400); color: #60a5fa; }
.quick-btn i { font-size: 20px; color: var(--blue-400); }

/* Top topographes list */
.topo-item {
    display:flex; align-items:center; gap:12px;
    padding:10px 0; border-bottom:1px solid var(--border);
}
.topo-item:last-child { border-bottom:none; padding-bottom:0; }
.topo-rank {
    width:26px;height:26px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-size:11px;font-weight:700;flex-shrink:0;
}

@media(max-width:1200px) { .stats-grid-admin { grid-template-columns: repeat(2,1fr); } }
@media(max-width:900px)  { .stats-grid-admin,.stats-grid-user { grid-template-columns: repeat(2,1fr); } }
@media(max-width:600px)  { .stats-grid-admin,.stats-grid-user { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')

@if($isAdmin)
{{-- ══════════════════════════════════════════════════════════
     TABLEAU DE BORD ADMINISTRATEUR
════════════════════════════════════════════════════════════ --}}

{{-- BANDEAU ADMIN --}}
<div style="background:linear-gradient(135deg,rgba(249,115,22,0.08),rgba(249,115,22,0.03));border:1px solid rgba(249,115,22,0.15);border-radius:12px;padding:14px 20px;margin-bottom:24px;display:flex;align-items:center;gap:14px;">
    <div style="width:40px;height:40px;background:rgba(249,115,22,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="bi bi-shield-lock-fill" style="color:#f97316;font-size:18px;"></i>
    </div>
    <div>
        <div style="font-size:14px;font-weight:700;color:#f97316;">Mode Administration</div>
        <div style="font-size:12px;color:var(--text-muted);">Vue globale de toute la plateforme TopoSmart — {{ now()->format('d/m/Y à H:i') }}</div>
    </div>
    <div style="margin-left:auto;text-align:right;">
        <div style="font-size:11px;color:var(--text-muted);">Connecté en tant que</div>
        <div style="font-size:13px;font-weight:600;color:var(--white);">{{ auth()->user()->name }}</div>
    </div>
</div>

{{-- STATS PRINCIPALES (8 cartes) --}}
<div class="stats-grid-admin">
    {{-- Utilisateurs --}}
    <div class="stat-card" style="--accent-color:#60a5fa;">
        <div class="stat-icon" style="background:rgba(59,130,246,0.12);color:#60a5fa;">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="stat-value" style="color:#60a5fa;">{{ $stats['total_users'] }}</div>
        <div class="stat-label">Utilisateurs total</div>
        <div style="margin-top:8px;font-size:11px;color:var(--text-muted);">
            {{ $stats['total_topographes'] }} topographe(s) &bull; {{ $stats['total_admins'] }} admin(s)
        </div>
    </div>

    {{-- Dossiers total --}}
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-folder2"></i></div>
        <div class="stat-value">{{ $stats['total_dossiers'] }}</div>
        <div class="stat-label">Dossiers total</div>
        <div style="margin-top:8px;font-size:11px;color:var(--text-muted);">
            @if($stats['total_dossiers'] > 0)
                {{ round(($stats['dossiers_termines'] / $stats['total_dossiers']) * 100) }}% complétés
            @else
                Aucun dossier
            @endif
        </div>
    </div>

    {{-- En cours --}}
    <div class="stat-card" style="--accent-color:#fbbf24;">
        <div class="stat-icon" style="background:rgba(251,191,36,0.12);color:#fbbf24;">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="stat-value" style="color:#fbbf24;">{{ $stats['dossiers_en_cours'] }}</div>
        <div class="stat-label">En cours</div>
        <div style="margin-top:8px;font-size:11px;color:var(--text-muted);">dossiers actifs</div>
    </div>

    {{-- Terminés --}}
    <div class="stat-card" style="--accent-color:#22c55e;">
        <div class="stat-icon" style="background:rgba(34,197,94,0.12);color:#4ade80;">
            <i class="bi bi-folder-check"></i>
        </div>
        <div class="stat-value" style="color:#4ade80;">{{ $stats['dossiers_termines'] }}</div>
        <div class="stat-label">Terminés</div>
        <div style="margin-top:8px;font-size:11px;color:var(--text-muted);">dossiers clôturés</div>
    </div>

    {{-- Documents --}}
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(168,85,247,0.12);color:#c084fc;">
            <i class="bi bi-files"></i>
        </div>
        <div class="stat-value" style="color:#c084fc;">{{ $stats['total_documents'] }}</div>
        <div class="stat-label">Documents téléversés</div>
        <div style="margin-top:8px;font-size:11px;color:var(--text-muted);">tous utilisateurs</div>
    </div>

    {{-- Types de dossiers --}}
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(20,184,166,0.12);color:#2dd4bf;">
            <i class="bi bi-tags-fill"></i>
        </div>
        <div class="stat-value" style="color:#2dd4bf;">{{ $stats['types_count'] }}</div>
        <div class="stat-label">Types de dossiers</div>
        <div style="margin-top:8px;font-size:11px;color:var(--text-muted);">types ANCFCC actifs</div>
    </div>

    {{-- Aujourd'hui --}}
    <div class="stat-card" style="--accent-color:#f97316;">
        <div class="stat-icon" style="background:rgba(249,115,22,0.12);color:#f97316;">
            <i class="bi bi-calendar-plus"></i>
        </div>
        <div class="stat-value" style="color:#f97316;">{{ $stats['dossiers_today'] }}</div>
        <div class="stat-label">Créés aujourd'hui</div>
        <div style="margin-top:8px;font-size:11px;color:var(--text-muted);">{{ now()->format('d/m/Y') }}</div>
    </div>

    {{-- Ce mois --}}
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(99,102,241,0.12);color:#818cf8;">
            <i class="bi bi-calendar-month"></i>
        </div>
        <div class="stat-value" style="color:#818cf8;">{{ $stats['dossiers_month'] }}</div>
        <div class="stat-label">Ce mois</div>
        <div style="margin-top:8px;font-size:11px;color:var(--text-muted);">{{ now()->translatedFormat('F Y') ?? now()->format('m/Y') }}</div>
    </div>
</div>

{{-- GRAPHIQUES + ACTIVITÉ --}}
<div class="row g-4 mb-4">

    {{-- Évolution mensuelle --}}
    <div class="col-lg-8">
        <div class="card-topo" style="height:100%;">
            <div class="section-title">
                <i class="bi bi-graph-up-arrow" style="color:#60a5fa;"></i>
                Évolution des dossiers (12 mois)
            </div>
            <div class="chart-wrapper" style="height:260px;">
                <canvas id="chartEvolution"></canvas>
            </div>
        </div>
    </div>

    {{-- Répartition par type --}}
    <div class="col-lg-4">
        <div class="card-topo" style="height:100%;">
            <div class="section-title">
                <i class="bi bi-pie-chart-fill" style="color:#f97316;"></i>
                Par type de dossier
            </div>
            @if($parType->isEmpty())
                <p style="color:var(--text-muted);font-size:13px;text-align:center;padding:40px 0;">Aucune donnée</p>
            @else
                <div class="chart-wrapper" style="height:200px;">
                    <canvas id="chartTypes"></canvas>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- TOP TOPOGRAPHES + ACTIVITÉ RÉCENTE --}}
<div class="row g-4 mb-4">

    {{-- Top topographes --}}
    <div class="col-lg-4">
        <div class="card-topo">
            <div class="section-title">
                <i class="bi bi-trophy-fill" style="color:#fbbf24;"></i>
                Top Topographes
            </div>
            @forelse($topTopographes as $i => $topo)
            <div class="topo-item">
                <div class="topo-rank" style="background:{{ $i === 0 ? 'rgba(251,191,36,0.2);color:#fbbf24;border:1px solid rgba(251,191,36,0.4)' : ($i === 1 ? 'rgba(148,163,184,0.2);color:#94a3b8;border:1px solid rgba(148,163,184,0.3)' : ($i === 2 ? 'rgba(180,83,9,0.2);color:#c2731a;border:1px solid rgba(180,83,9,0.3)' : 'rgba(255,255,255,0.06);color:var(--text-muted);border:1px solid var(--border)')) }}">
                    {{ $i + 1 }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13px;font-weight:600;color:var(--white);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $topo->name }}
                    </div>
                    <div style="font-size:11px;color:var(--text-muted);">{{ $topo->email }}</div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <div style="font-size:16px;font-weight:800;color:#60a5fa;">{{ $topo->dossiers_count }}</div>
                    <div style="font-size:10px;color:var(--text-muted);">dossiers</div>
                </div>
            </div>
            @empty
            <p style="color:var(--text-muted);font-size:13px;text-align:center;padding:20px 0;">Aucun utilisateur</p>
            @endforelse
        </div>
    </div>

    {{-- Activité récente --}}
    <div class="col-lg-8">
        <div class="card-topo">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div class="section-title" style="margin-bottom:0;">
                    <i class="bi bi-activity" style="color:#60a5fa;"></i>
                    Activité récente (tous utilisateurs)
                </div>
                <a href="{{ route('admin.dossiers.index') }}" class="btn-outline-orange" style="font-size:11px;padding:4px 10px;">
                    Voir tout <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if($derniersDossiers->isEmpty())
                <div style="text-align:center;padding:32px;color:var(--text-muted);">
                    <i class="bi bi-inbox" style="font-size:36px;opacity:0.3;display:block;margin-bottom:10px;"></i>
                    Aucun dossier créé
                </div>
            @else
                <table class="table-topo">
                    <thead>
                        <tr>
                            <th>Propriétaire</th>
                            <th>Type</th>
                            <th>Topographe</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($derniersDossiers as $dossier)
                        <tr>
                            <td>
                                <strong>{{ $dossier->proprietaire }}</strong>
                                @if($dossier->localisation)
                                    <div style="font-size:11px;color:var(--text-muted);">
                                        <i class="bi bi-geo-alt"></i> {{ $dossier->localisation }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge-topo badge-info" style="font-size:10px;">{{ $dossier->type_label }}</span>
                            </td>
                            <td style="font-size:12px;color:#94a3b8;">
                                <i class="bi bi-person"></i> {{ $dossier->user?->name ?? '—' }}
                            </td>
                            <td style="color:var(--text-muted);font-size:12px;">
                                {{ $dossier->date_creation->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="badge-topo badge-{{ $dossier->statut_badge }}">
                                    {{ $dossier->statut_label }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.dossiers.show', $dossier) }}"
                                   style="color:#60a5fa;font-size:16px;">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

@else
{{-- ══════════════════════════════════════════════════════════
     TABLEAU DE BORD UTILISATEUR (Topographe)
════════════════════════════════════════════════════════════ --}}

{{-- Stats personnelles --}}
<div class="stats-grid-user">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-folder2"></i></div>
        <div class="stat-value">{{ $totalDossiers }}</div>
        <div class="stat-label">Mes dossiers</div>
    </div>
    <div class="stat-card" style="--accent-color:#fbbf24;">
        <div class="stat-icon" style="background:rgba(251,191,36,0.15);color:#fbbf24;">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="stat-value" style="color:#fbbf24;">{{ $enCours }}</div>
        <div class="stat-label">En cours</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(34,197,94,0.15);color:#22c55e;">
            <i class="bi bi-check2-circle"></i>
        </div>
        <div class="stat-value" style="color:#22c55e;">{{ $termines }}</div>
        <div class="stat-label">Terminés</div>
    </div>
</div>

<div class="row g-4">
    {{-- Derniers dossiers --}}
    <div class="col-lg-8">
        <div class="card-topo">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <h6 class="section-title" style="margin-bottom:0;">
                    <i class="bi bi-clock-history" style="color:#60a5fa;"></i>
                    Derniers dossiers
                </h6>
                <a href="{{ route('dossiers.index') }}" class="btn-outline-orange" style="font-size:12px;padding:5px 12px;">
                    Voir tout <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if($derniersDossiers->isEmpty())
                <div style="text-align:center;padding:48px 0;color:var(--text-muted);">
                    <i class="bi bi-folder-x" style="font-size:48px;opacity:0.4;display:block;margin-bottom:14px;"></i>
                    <p>Aucun dossier pour l'instant.</p>
                    <a href="{{ route('dossiers.create') }}" class="btn-orange mt-2">
                        <i class="bi bi-plus-lg"></i> Créer un dossier
                    </a>
                </div>
            @else
                <table class="table-topo">
                    <thead>
                        <tr>
                            <th>Propriétaire</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($derniersDossiers as $dossier)
                        <tr>
                            <td>
                                <strong>{{ $dossier->proprietaire }}</strong>
                                @if($dossier->localisation)
                                    <div style="font-size:11px;color:var(--text-muted);">
                                        <i class="bi bi-geo-alt"></i> {{ $dossier->localisation }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge-topo badge-info" style="font-size:11px;">{{ $dossier->type_label }}</span>
                            </td>
                            <td style="color:var(--text-muted);font-size:13px;">
                                {{ $dossier->date_creation->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="badge-topo badge-{{ $dossier->statut_badge }}">{{ $dossier->statut_label }}</span>
                            </td>
                            <td>
                                <a href="{{ route('dossiers.show', $dossier) }}"
                                   style="color:var(--blue-400);font-size:18px;"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Graphique + Actions rapides --}}
    <div class="col-lg-4">
        <div class="card-topo mb-4">
            <h6 class="section-title">
                <i class="bi bi-pie-chart" style="color:#60a5fa;"></i>
                Dossiers par type
            </h6>
            @if($parType->isEmpty())
                <p style="color:var(--text-muted);font-size:13px;text-align:center;padding:20px 0;">Aucune donnée</p>
            @else
                <div class="chart-wrapper">
                    <canvas id="chartTypes"></canvas>
                </div>
            @endif
        </div>

        <div class="card-topo">
            <h6 class="section-title">
                <i class="bi bi-lightning-fill" style="color:#fbbf24;"></i>
                Actions rapides
            </h6>
            <div class="quick-actions">
                <a href="{{ route('dossiers.create') }}" class="quick-btn">
                    <i class="bi bi-folder-plus"></i>
                    <span>Nouveau dossier</span>
                </a>
                <a href="{{ route('outils.index') }}" class="quick-btn">
                    <i class="bi bi-calculator"></i>
                    <span>Calcul distance</span>
                </a>
                <a href="{{ route('chatbot.index') }}" class="quick-btn">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span>Poser question</span>
                </a>
                <a href="{{ route('dossiers.index') }}?statut=en_cours" class="quick-btn">
                    <i class="bi bi-hourglass-split"></i>
                    <span>En cours</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══════ CARTE LEAFLET (commune admin + user) ══════ --}}
@if($dossiersCarte->count() > 0)
<div class="card-topo mt-4" id="dashboard-map-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <h6 class="section-title" style="margin-bottom:0;">
            <i class="bi bi-globe2" style="color:#60a5fa;"></i>
            {{ $isAdmin ? 'Carte de tous les dossiers géolocalisés' : 'Carte de mes dossiers' }}
        </h6>
        <span style="font-size:12px;color:var(--text-muted);">
            <span style="color:#4ade80;font-weight:700;">{{ $dossiersCarte->count() }}</span> géolocalisé(s)
        </span>
    </div>
    <div style="display:flex;gap:16px;margin-bottom:12px;font-size:12px;flex-wrap:wrap;">
        <span style="display:flex;align-items:center;gap:6px;">
            <span style="width:12px;height:12px;border-radius:50%;background:#f59e0b;display:inline-block;"></span> En cours
        </span>
        <span style="display:flex;align-items:center;gap:6px;">
            <span style="width:12px;height:12px;border-radius:50%;background:#22c55e;display:inline-block;"></span> Terminé
        </span>
    </div>
    <div id="dashboard-map" style="height:420px;border-radius:10px;border:1px solid var(--border);overflow:hidden;"></div>
</div>
@else
<div class="card-topo mt-4" style="text-align:center;padding:24px;border-style:dashed;">
    <i class="bi bi-map" style="font-size:32px;color:var(--text-muted);opacity:0.3;display:block;margin-bottom:10px;"></i>
    <p style="color:var(--text-muted);font-size:13px;margin:0;">
        {{ $isAdmin ? 'Aucun dossier géolocalisé sur la plateforme.' : 'Aucun dossier géolocalisé. Ajoutez une position GPS à vos dossiers.' }}
    </p>
    @if(!$isAdmin)
    <a href="{{ route('dossiers.create') }}" class="btn-orange mt-3" style="display:inline-flex;">
        <i class="bi bi-plus-lg"></i> Créer un dossier
    </a>
    @endif
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── GRAPHIQUES ──────────────────────────────────────────────────────────────
const chartDefaults = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
            labels: { color: '#94a3b8', padding: 16, usePointStyle: true, pointStyle: 'circle', font: { size: 11, family: 'Inter' } }
        },
        tooltip: {
            backgroundColor: '#0f172a', titleColor: '#fff', bodyColor: '#94a3b8',
            borderColor: '#334155', borderWidth: 1, padding: 12
        }
    }
};

@if($isAdmin && !empty($evolutionLabels))
// Graphique évolution mensuelle
const ctxEvo = document.getElementById('chartEvolution')?.getContext('2d');
if (ctxEvo) {
    new Chart(ctxEvo, {
        type: 'bar',
        data: {
            labels: {!! json_encode($evolutionLabels) !!},
            datasets: [{
                label: 'Dossiers créés',
                data: {!! json_encode($evolutionData) !!},
                backgroundColor: 'rgba(37,99,235,0.6)',
                borderColor: '#2563eb',
                borderWidth: 1,
                borderRadius: 5,
                borderSkipped: false,
            }]
        },
        options: {
            ...chartDefaults,
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#64748b', font: { size: 10 } } },
                y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#64748b', font: { size: 10 }, stepSize: 1 }, beginAtZero: true }
            }
        }
    });
}
@endif

@if(!$parType->isEmpty())
// Graphique doughnut par type
const ctxTypes = document.getElementById('chartTypes')?.getContext('2d');
if (ctxTypes) {
    new Chart(ctxTypes, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($parType->pluck('nom')) !!},
            datasets: [{
                data: {!! json_encode($parType->pluck('total')) !!},
                backgroundColor: {!! json_encode($parType->pluck('color')) !!},
                borderWidth: 2,
                borderColor: '#071e3d',
                hoverOffset: 10,
            }]
        },
        options: { ...chartDefaults, cutout: '72%' }
    });
}
@endif
</script>

@if($dossiersCarte->count() > 0)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function() {
    const dossiers = {!! json_encode($dossiersCarte->map(fn($d) => [
        'id'          => $d->id,
        'proprietaire'=> $d->proprietaire,
        'type'        => $d->type_label,
        'statut'      => $d->statut,
        'statut_label'=> $d->statut_label,
        'lat'         => $d->lat,
        'lng'         => $d->lng,
        'localisation'=> $d->localisation,
        'date'        => $d->date_creation->format('d/m/Y'),
        'url'         => route($isAdmin ? 'admin.dossiers.show' : 'dossiers.show', $d),
    ])) !!};

    if (!dossiers.length) return;

    const avgLat = dossiers.reduce((s, d) => s + d.lat, 0) / dossiers.length;
    const avgLng = dossiers.reduce((s, d) => s + d.lng, 0) / dossiers.length;

    const map = L.map('dashboard-map', { scrollWheelZoom: false })
        .setView([avgLat, avgLng], dossiers.length === 1 ? 13 : 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap', maxZoom: 19
    }).addTo(map);

    dossiers.forEach(function(d) {
        const color = d.statut === 'termine' ? '#22c55e' : '#f59e0b';
        const icon  = L.divIcon({
            className: '',
            html: `<div style="width:30px;height:30px;background:${color};border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 3px 10px rgba(0,0,0,0.4);"></div>`,
            iconSize: [30, 30], iconAnchor: [15, 30], popupAnchor: [0, -34]
        });

        const labelVoir = {{ $isAdmin ? 'true' : 'false' }} ? 'Voir (Admin)' : 'Voir le dossier';

        L.marker([d.lat, d.lng], { icon })
            .addTo(map)
            .bindPopup(`
                <div style="font-family:Inter,sans-serif;min-width:190px;padding:4px;">
                    <div style="font-weight:700;font-size:13px;color:#0f172a;margin-bottom:5px;">${d.proprietaire}</div>
                    <div style="font-size:11px;color:#64748b;margin-bottom:3px;"><b>Type :</b> ${d.type}</div>
                    <div style="font-size:11px;color:#64748b;margin-bottom:3px;"><b>Statut :</b> <span style="color:${color};font-weight:600;">${d.statut_label}</span></div>
                    ${d.localisation ? `<div style="font-size:10px;color:#94a3b8;margin-bottom:3px;">${d.localisation}</div>` : ''}
                    <div style="font-size:10px;color:#94a3b8;margin-bottom:8px;">${d.date}</div>
                    <a href="${d.url}" style="display:block;text-align:center;padding:6px;background:#2563eb;color:white;border-radius:5px;text-decoration:none;font-size:11px;font-weight:600;">${labelVoir}</a>
                </div>
            `, { maxWidth: 220 })
            .on('mouseover', function() { this.openPopup(); });
    });

    if (dossiers.length > 1) {
        map.fitBounds(L.latLngBounds(dossiers.map(d => [d.lat, d.lng])), { padding: [40, 40] });
    }
})();
</script>
@endif
@endpush
