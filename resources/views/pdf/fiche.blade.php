<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fiche Dossier — {{ $dossier->proprietaire }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, serif;
            font-size: 10pt; color: #1e293b; background: #ffffff;
        }

        /* ── EN-TÊTE ── */
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
            color: white; padding: 0; margin-bottom: 0;
        }
        .header-inner { padding: 16px 28px 12px; }
        .header-logo  { float: left; width: 55%; }
        .header-meta  { float: right; width: 44%; text-align: right; font-size: 8.5pt; color: rgba(255,255,255,0.7); }
        .header-title { font-size: 17pt; font-weight: bold; color: #fff; letter-spacing: 1px; }
        .header-sub   { font-size: 8.5pt; color: rgba(255,255,255,0.6); margin-top: 2px; }
        .clearfix::after { content: ''; display: table; clear: both; }

        .accent-band  {
            background: #f97316; color: white;
            padding: 7px 28px; font-size: 10.5pt;
            font-weight: bold; letter-spacing: 0.5px;
        }

        /* ── CARTE IDENTITÉ ── */
        .id-card {
            margin: 16px 28px;
            border: 2px solid #0f172a;
            border-radius: 6px; overflow: hidden;
        }
        .id-card-header {
            background: #0f172a; color: white;
            padding: 10px 16px; font-size: 10pt;
            font-weight: bold; letter-spacing: 0.5px;
        }
        .id-card-body { padding: 0; }
        .id-row {
            display: flex; border-bottom: 1px solid #e2e8f0;
        }
        .id-row:last-child { border-bottom: none; }
        .id-label {
            width: 38%; padding: 8px 14px;
            font-size: 9pt; font-weight: bold;
            color: #475569; background: #f8fafc;
            border-right: 1px solid #e2e8f0;
        }
        .id-value {
            width: 62%; padding: 8px 14px;
            font-size: 9.5pt; color: #1e293b;
        }

        /* ── STATUT BADGE ── */
        .status-pill {
            display: inline-block; padding: 4px 14px;
            border-radius: 20px; font-size: 9pt; font-weight: bold;
        }
        .status-en_cours { background: #fef3c7; color: #92400e; border: 1px solid #f59e0b; }
        .status-termine  { background: #dcfce7; color: #14532d; border: 1px solid #22c55e; }

        /* ── SECTIONS ── */
        .section { margin: 0 28px 16px; }
        .section-title {
            font-size: 10pt; font-weight: bold; color: #f97316;
            border-bottom: 2px solid #f97316;
            padding-bottom: 4px; margin-bottom: 10px;
            text-transform: uppercase; letter-spacing: 0.5px;
        }

        /* ── GRILLE STATS ── */
        .stats-grid { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .stat-cell {
            width: 25%; text-align: center; padding: 12px 6px;
            border: 1px solid #e2e8f0; border-radius: 4px;
        }
        .stat-num { font-size: 18pt; font-weight: bold; color: #0f172a; }
        .stat-lbl { font-size: 7.5pt; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }

        /* ── CHECKLIST ── */
        .checklist-grid { width: 100%; }
        .cl-item {
            float: left; width: 48%; margin-bottom: 6px;
            padding: 6px 10px 6px 8px;
            border: 1px solid #e2e8f0; border-radius: 4px;
            background: #fafbfc; font-size: 8.5pt;
        }
        .cl-item.done { background: #f0fdf4; border-color: #bbf7d0; }
        .cl-icon { font-size: 10pt; margin-right: 6px; }
        .cl-odd  { margin-right: 4%; }

        /* ── DOCUMENTS TABLE ── */
        .doc-table { width: 100%; border-collapse: collapse; font-size: 9pt; }
        .doc-table th {
            background: #0f172a; color: white;
            padding: 7px 10px; text-align: left; font-size: 8.5pt;
        }
        .doc-table td { padding: 6px 10px; border-bottom: 1px solid #e2e8f0; }
        .doc-table tr:nth-child(even) td { background: #f8fafc; }

        /* ── BARRE PROGRESSION ── */
        .progress-bar {
            background: #e2e8f0; border-radius: 6px;
            height: 10px; overflow: hidden; margin: 6px 0;
        }
        .progress-fill { height: 100%; border-radius: 6px; }

        /* ── PIED DE PAGE FICHE ── */
        .fiche-footer {
            margin: 16px 28px 28px;
            padding: 12px 14px;
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 4px; font-size: 8.5pt; color: #64748b;
        }
        .ref-num {
            font-size: 12pt; font-weight: bold; color: #0f172a;
            letter-spacing: 1px; float: right;
        }

        /* ── SIGNATURE MINI ── */
        .mini-sig { float: left; width: 30%; margin-right: 3%; text-align: center; }
        .mini-sig:last-child { margin-right: 0; }
        .mini-line {
            border-bottom: 1px solid #475569;
            height: 35px; margin-bottom: 4px;
        }
        .mini-lbl { font-size: 8pt; color: #64748b; }

        /* ── FIXED FOOTER ── */
        .footer {
            position: fixed; bottom: 0; left: 0; right: 0;
            padding: 6px 28px; background: #0f172a;
            font-size: 7.5pt; color: rgba(255,255,255,0.6);
        }
        .footer-l { float: left; }
        .footer-r { float: right; }
    </style>
</head>
<body>

{{-- FOOTER FIXE --}}
<div class="footer">
    <div class="footer-l">ANCFCC — Agence Nationale de la Conservation Foncière, du Cadastre et de la Cartographie</div>
    <div class="footer-r">Fiche Dossier #{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }} &bull; {{ now()->format('d/m/Y') }}</div>
    <div class="clearfix"></div>
</div>

{{-- EN-TÊTE --}}
<div class="header">
    <div class="header-inner clearfix">
        <div class="header-logo">
            <div class="header-title">TOPOSMART</div>
            <div class="header-sub">Plateforme Intelligente de Gestion des Dossiers Topographiques</div>
        </div>
        <div class="header-meta">
            <strong>ANCFCC — MAROC</strong><br>
            {{ now()->format('d/m/Y') }}<br>
            Réf : FD-{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }}
        </div>
    </div>
    <div class="accent-band">
        FICHE DOSSIER — {{ strtoupper($dossier->type_label) }}
    </div>
</div>

{{-- CARTE IDENTITÉ --}}
<div class="id-card">
    <div class="id-card-header">
        Identite du Dossier — Ref. FD-{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }}
    </div>
    <div class="id-card-body">
        <div class="id-row">
            <div class="id-label">Propriétaire</div>
            <div class="id-value"><strong>{{ strtoupper($dossier->proprietaire) }}</strong></div>
        </div>
        <div class="id-row">
            <div class="id-label">Type de dossier</div>
            <div class="id-value">{{ $dossier->type_label }}</div>
        </div>
        <div class="id-row">
            <div class="id-label">Statut</div>
            <div class="id-value">
                <span class="status-pill status-{{ $dossier->statut }}">{{ $dossier->statut_label }}</span>
            </div>
        </div>
        <div class="id-row">
            <div class="id-label">Date de création</div>
            <div class="id-value">{{ $dossier->date_creation->format('d/m/Y') }}</div>
        </div>
        <div class="id-row">
            <div class="id-label">Localisation</div>
            <div class="id-value">{{ $dossier->localisation ?? '— Non précisée —' }}</div>
        </div>
        @if($dossier->lat && $dossier->lng)
        <div class="id-row">
            <div class="id-label">Coordonnées GPS</div>
            <div class="id-value">Lat : {{ number_format($dossier->lat, 6) }}° &nbsp;|&nbsp; Lng : {{ number_format($dossier->lng, 6) }}°</div>
        </div>
        @endif
        <div class="id-row">
            <div class="id-label">Topographe</div>
            <div class="id-value">{{ $dossier->user->name ?? 'N/A' }}</div>
        </div>
        @if($dossier->description)
        <div class="id-row">
            <div class="id-label">Description</div>
            <div class="id-value" style="font-size:9pt;color:#475569;">{{ Str::limit($dossier->description, 200) }}</div>
        </div>
        @endif
    </div>
</div>

@php
    $checklist     = $dossier->checklist;
    $uploadedNames = $dossier->documents->pluck('nom')->toArray();
    $uploadedTypes = $dossier->documents->pluck('type_document')->toArray();
    $doneCount = collect($checklist)->filter(function($item) use ($uploadedNames, $uploadedTypes) {
        $keyword = explode(' ', $item)[0];
        foreach($uploadedNames as $uname) {
            if(stripos($uname, $keyword) !== false) return true;
        }
        if(in_array($item, $uploadedTypes)) return true;
        return false;
    })->count();
    $pct = count($checklist) > 0 ? round(($doneCount / count($checklist)) * 100) : 0;
    $progressColor = $pct == 100 ? '#22c55e' : ($pct >= 50 ? '#f59e0b' : '#ef4444');
@endphp

{{-- PROGRESSION --}}
<div class="section">
    <div class="section-title">Avancement du Dossier</div>
    <div style="font-size:9pt;color:#475569;margin-bottom:4px;">
        Complétion : <strong style="color:#0f172a;">{{ $doneCount }}/{{ count($checklist) }} documents</strong>
        <span style="float:right;font-size:12pt;font-weight:bold;color:{{ $progressColor }};">{{ $pct }}%</span>
    </div>
    <div class="progress-bar">
        <div class="progress-fill" style="width:{{ $pct }}%;background:{{ $progressColor }};"></div>
    </div>
</div>

{{-- CHECKLIST --}}
@if(count($checklist) > 0)
<div class="section">
    <div class="section-title">Checklist ANCFCC — Documents Requis</div>
    <div class="clearfix">
        @foreach($checklist as $idx => $item)
            @php
                $isDone = false;
                $keyword = explode(' ', $item)[0];
                foreach($uploadedNames as $uname) {
                    if(stripos($uname, $keyword) !== false) { $isDone = true; break; }
                }
                if(!$isDone && in_array($item, $uploadedTypes)) $isDone = true;
                $isOdd = ($idx % 2 === 0);
            @endphp
            <div class="cl-item {{ $isDone ? 'done' : '' }} {{ $isOdd ? 'cl-odd' : '' }}">
                <span class="cl-icon" style="display:inline-block;width:14px;height:14px;border:1.5px solid {{ $isDone ? '#22c55e' : '#94a3b8' }};border-radius:2px;background:{{ $isDone ? '#22c55e' : 'transparent' }};margin-right:2px;flex-shrink:0;font-size:9pt;text-align:center;line-height:13px;color:white;font-weight:bold;">{{ $isDone ? 'v' : '' }}</span>
                <span style="color:{{ $isDone ? '#15803d' : '#64748b' }};">{{ $item }}</span>
            </div>
            @if(!$isOdd)<div class="clearfix"></div>@endif
        @endforeach
        <div class="clearfix"></div>
    </div>
</div>
@endif

{{-- LISTE DOCUMENTS --}}
<div class="section">
    <div class="section-title">Liste des Documents Fournis ({{ $dossier->documents->count() }})</div>

    @if($dossier->documents->isEmpty())
        <p style="font-size:9pt;color:#94a3b8;padding:12px;background:#f8fafc;border:1px dashed #e2e8f0;border-radius:4px;text-align:center;">
            Aucun document n'a encore été téléversé pour ce dossier.
        </p>
    @else
        <table class="doc-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom du document</th>
                    <th>Catégorie</th>
                    <th>Format</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dossier->documents as $i => $doc)
                <tr>
                    <td style="font-weight:bold;color:#0f172a;">{{ $i + 1 }}</td>
                    <td><strong>{{ $doc->nom }}</strong></td>
                    <td>{{ $doc->type_document }}</td>
                    <td>
                        <span style="background:#e2e8f0;padding:2px 6px;border-radius:3px;font-size:8pt;font-weight:bold;">
                            {{ strtoupper($doc->extension ?? '?') }}
                        </span>
                    </td>
                    <td style="color:#64748b;">{{ $doc->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- SIGNATURES --}}
<div class="section">
    <div class="section-title">Validation et Signatures</div>
    <div class="clearfix" style="margin-top:8px;">
        <div class="mini-sig">
            <div class="mini-line"></div>
            <div class="mini-lbl">Le Propriétaire</div>
            <div class="mini-lbl" style="font-size:7.5pt;margin-top:2px;">{{ $dossier->proprietaire }}</div>
        </div>
        <div class="mini-sig">
            <div class="mini-line"></div>
            <div class="mini-lbl">Le Topographe</div>
            <div class="mini-lbl" style="font-size:7.5pt;margin-top:2px;">{{ $dossier->user->name ?? '—' }}</div>
        </div>
        <div class="mini-sig">
            <div class="mini-line"></div>
            <div class="mini-lbl">Visa ANCFCC</div>
            <div class="mini-lbl" style="font-size:7.5pt;margin-top:2px;">Cachet officiel</div>
        </div>
    </div>
</div>

{{-- PIED FICHE --}}
<div class="fiche-footer">
    <div class="ref-num">FD-{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }}</div>
    Document généré automatiquement par TopoSmart le {{ now()->format('d/m/Y à H:i') }}.
    Ce document est à usage interne ANCFCC — {{ $dossier->type_label }}.
    <div class="clearfix"></div>
</div>

</body>
</html>
