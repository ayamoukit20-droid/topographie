@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@push('styles')
<style>
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }
.chart-container { position: relative; height: 200px; }
.recent-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 16px;
}
.section-title {
    font-size: 16px; font-weight: 700; color: var(--text-light);
}
.quick-actions { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
.quick-btn {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 16px;
    background: var(--navy);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text-light);
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s;
}
.quick-btn:hover { border-color: var(--orange); color: var(--orange); }
.quick-btn i { font-size: 20px; color: var(--orange); }
@media(max-width:1100px){ .stats-grid{ grid-template-columns: repeat(2,1fr); } }
@media(max-width:600px){ .stats-grid{ grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-folder2"></i></div>
        <div class="stat-value">{{ $totalDossiers }}</div>
        <div class="stat-label">Total dossiers</div>
    </div>
    <div class="stat-card" style="--accent:#fbbf24;">
        <div class="stat-icon" style="background:rgba(251,191,36,0.15);color:#fbbf24;"><i class="bi bi-hourglass-split"></i></div>
        <div class="stat-value" style="color:#fbbf24;">{{ $enCours }}</div>
        <div class="stat-label">En cours</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(59,130,246,0.15);color:#3b82f6;"><i class="bi bi-patch-check"></i></div>
        <div class="stat-value" style="color:#3b82f6;">{{ $valides }}</div>
        <div class="stat-label">Validés</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(34,197,94,0.15);color:#22c55e;"><i class="bi bi-check2-circle"></i></div>
        <div class="stat-value" style="color:#22c55e;">{{ $termines }}</div>
        <div class="stat-label">Terminés</div>
    </div>
</div>

<div class="row g-4">
    <!-- Derniers dossiers -->
    <div class="col-lg-8">
        <div class="card-topo">
            <div class="recent-header">
                <h6 class="section-title mb-0">Derniers dossiers</h6>
                <a href="{{ route('dossiers.index') }}" class="btn-outline-orange" style="font-size:12px;padding:5px 12px;">
                    Voir tout <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if($derniersDossiers->isEmpty())
                <div class="text-center py-5" style="color:var(--text-muted);">
                    <i class="bi bi-folder-x" style="font-size:48px;opacity:0.4;"></i>
                    <p class="mt-3">Aucun dossier pour l'instant.<br>Créez votre premier dossier !</p>
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
                                <span class="badge-topo badge-secondary">{{ $dossier->type_label }}</span>
                            </td>
                            <td style="color:var(--text-muted);font-size:13px;">
                                {{ $dossier->date_creation->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="badge-topo badge-{{ $dossier->statut_badge }}">
                                    {{ $dossier->statut_label }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('dossiers.show', $dossier) }}"
                                   style="color:var(--orange);text-decoration:none;font-size:18px;">
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

    <!-- Graphique + Actions rapides -->
    <div class="col-lg-4">
        <div class="card-topo mb-4">
            <h6 class="section-title mb-4">Dossiers par type</h6>
            @if($parType->isEmpty())
                <p style="color:var(--text-muted);font-size:13px;text-align:center;padding:20px 0;">
                    Aucune donnée disponible
                </p>
            @else
                <canvas id="chartTypes" class="chart-container"></canvas>
            @endif
        </div>

        <div class="card-topo">
            <h6 class="section-title mb-4">Actions rapides</h6>
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

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
@if(!$parType->isEmpty())
const ctx = document.getElementById('chartTypes').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($parType->keys()->map(fn($k) => ucfirst($k))) !!},
        datasets: [{
            data: {!! json_encode($parType->values()) !!},
            backgroundColor: ['#f97316','#3b82f6','#22c55e','#fbbf24','#8b5cf6','#ec4899'],
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { color: '#94a3b8', padding: 16, font: { size: 11 } }
            }
        }
    }
});
@endif
</script>
@endpush
