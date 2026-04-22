<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rapport Technique — {{ $dossier->proprietaire }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, serif;
            font-size: 11pt;
            color: #1e293b;
            background: #ffffff;
        }

        /* ── EN-TÊTE ── */
        .header {
            background: #03122b;
            color: white;
            padding: 22px 30px;
            margin-bottom: 0;
        }
        .header-logo    { float: left; width: 60%; }
        .header-meta    { float: right; width: 40%; text-align: right; font-size: 9pt; color: rgba(255,255,255,0.7); }
        .header-title   { font-size: 18pt; font-weight: bold; color: #ffffff; margin-bottom: 3px; }
        .header-subtitle{ font-size: 10pt; color: rgba(255,255,255,0.65); }
        .clearfix::after{ content: ''; display: table; clear: both; }

        .blue-band {
            background: #2563eb;
            color: white; padding: 10px 30px;
            font-size: 12pt; font-weight: bold;
            letter-spacing: 0.5px; margin-bottom: 24px;
        }

        .content { padding: 0 30px 30px; }

        .section { margin-bottom: 22px; }
        .section-title {
            font-size: 11pt; font-weight: bold; color: #1a56b0;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 5px; margin-bottom: 12px;
            text-transform: uppercase; letter-spacing: 0.5px;
        }

        /* Info grid */
        .info-grid { width: 100%; }
        .info-grid td { padding: 7px 10px; font-size: 10pt; vertical-align: top; }
        .info-grid tr:nth-child(even) td { background: #f8fafc; }
        .info-label { font-weight: bold; color: #475569; width: 35%; }
        .info-value { color: #1e293b; }

        /* Badge statut */
        .badge          { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 9pt; font-weight: bold; }
        .badge-warning  { background: #fef3c7; color: #92400e; border: 1px solid #f59e0b; }
        .badge-success  { background: #dcfce7; color: #14532d; border: 1px solid #22c55e; }

        /* Résumé / stats */
        .stat-row { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .stat-box {
            width: 25%; text-align: center; padding: 12px 8px;
            background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px;
        }
        .stat-num  { font-size: 20pt; font-weight: bold; color: #2563eb; }
        .stat-lbl  { font-size: 8.5pt; color: #64748b; margin-top: 2px; }

        /* Checklist */
        .checklist { list-style: none; padding: 0; }
        .checklist li {
            padding: 7px 12px; font-size: 10pt;
            border-bottom: 1px solid #e2e8f0; display: flex;
        }
        .checklist li:last-child { border-bottom: none; }
        .check-box {
            display: inline-block; width: 16px; height: 16px;
            border: 2px solid #cbd5e1; border-radius: 3px;
            margin-right: 10px; margin-top: 1px; flex-shrink: 0;
        }
        .check-box.done {
            background: #22c55e; border-color: #22c55e;
            color: white; text-align: center;
            line-height: 12px; font-size: 9pt;
        }

        /* Documents table */
        .doc-table { width: 100%; border-collapse: collapse; font-size: 10pt; }
        .doc-table th {
            background: #1e40af; color: white;
            padding: 8px 10px; text-align: left;
            font-size: 9pt; font-weight: bold;
        }
        .doc-table td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; color: #1e293b; }
        .doc-table tr:nth-child(even) td { background: #f8fafc; }

        /* Résumé texte */
        .summary-box {
            background: #eff6ff; border: 1px solid #bfdbfe;
            border-left: 4px solid #2563eb;
            padding: 14px; border-radius: 2px; font-size: 10pt;
            line-height: 1.75; color: #1e40af;
        }

        /* Footer */
        .footer {
            position: fixed; bottom: 0; left: 0; right: 0;
            padding: 8px 30px; background: #f8fafc;
            border-top: 2px solid #e2e8f0;
            font-size: 8pt; color: #94a3b8;
        }
        .footer-left  { float: left; }
        .footer-right { float: right; }

        /* Signature block */
        .sig-col { float: left; width: 45%; }
        .sig-line { border-bottom: 1px solid #475569; height: 40px; margin: 10px 0 5px; }
        .sig-label { font-size: 9pt; color: #64748b; }
    </style>
</head>
<body>

{{-- FOOTER --}}
<div class="footer">
    <div class="footer-left">
        ANCFCC — Agence Nationale de la Conservation Foncière, du Cadastre et de la Cartographie
    </div>
    <div class="footer-right">
        Rapport Technique #{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }} &bull; Généré le {{ now()->format('d/m/Y à H:i') }}
    </div>
    <div class="clearfix"></div>
</div>

{{-- EN-TETE --}}
<div class="header">
    <div class="clearfix">
        <div class="header-logo">
            <div class="header-title">TOPOSMART</div>
            <div class="header-subtitle">Plateforme Intelligente de Gestion des Dossiers Topographiques</div>
        </div>
        <div class="header-meta">
            <strong>ANCFCC — MAROC</strong><br>
            Dossier #{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }}<br>
            {{ now()->format('d/m/Y') }}
        </div>
    </div>
</div>

<div class="blue-band">
    RAPPORT TECHNIQUE — {{ strtoupper($dossier->type_label) }}
</div>

<div class="content">

    {{-- RÉSUMÉ STATISTIQUES --}}
    @php
        $checklist    = $dossier->checklist;
        $uploadedNames= $dossier->documents->pluck('nom')->toArray();
        $uploadedTypes= $dossier->documents->pluck('type_document')->toArray();
        $doneCount = collect($checklist)->filter(function($item) use ($uploadedNames, $uploadedTypes) {
            $keyword = explode(' ', $item)[0];
            foreach($uploadedNames as $uname) {
                if(stripos($uname, $keyword) !== false) return true;
            }
            if(in_array($item, $uploadedTypes)) return true;
            return false;
        })->count();
        $pct = count($checklist) > 0 ? round(($doneCount / count($checklist)) * 100) : 0;
    @endphp

    {{-- 1. INFORMATIONS GÉNÉRALES --}}
    <div class="section">
        <div class="section-title">1. Informations générales du dossier</div>
        <table class="info-grid">
            <tr>
                <td class="info-label">Référence dossier</td>
                <td class="info-value">#{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td class="info-label">Type de dossier</td>
                <td class="info-value">{{ $dossier->type_label }}</td>
            </tr>
            <tr>
                <td class="info-label">Propriétaire</td>
                <td class="info-value"><strong>{{ $dossier->proprietaire }}</strong></td>
                <td class="info-label">Statut</td>
                <td class="info-value">
                    @php
                        $badgeClass = match($dossier->statut) {
                            'en_cours' => 'badge-warning',
                            'termine'  => 'badge-success',
                            default    => ''
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $dossier->statut_label }}</span>
                </td>
            </tr>
            <tr>
                <td class="info-label">Date de création</td>
                <td class="info-value">{{ $dossier->date_creation->format('d/m/Y') }}</td>
                <td class="info-label">Créé par</td>
                <td class="info-value">{{ $dossier->user->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="info-label">Localisation</td>
                <td class="info-value" colspan="3">{{ $dossier->localisation ?? '—' }}</td>
            </tr>
            @if($dossier->lat && $dossier->lng)
            <tr>
                <td class="info-label">Coordonnées GPS</td>
                <td class="info-value" colspan="3">
                    Lat : {{ $dossier->lat }} &nbsp;|&nbsp; Lng : {{ $dossier->lng }}
                </td>
            </tr>
            @endif
        </table>
    </div>

    {{-- 2. DESCRIPTION --}}
    @if($dossier->description)
    <div class="section">
        <div class="section-title">2. Description technique</div>
        <p style="font-size:10pt;line-height:1.7;color:#334155;padding:10px;background:#f8fafc;border-left:3px solid #2563eb;">
            {{ $dossier->description }}
        </p>
    </div>
    @endif

    {{-- 3. LOCALISATION --}}
    @if($dossier->lat && $dossier->lng)
    <div class="section">
        <div class="section-title">3. Localisation géographique</div>
        <table class="info-grid">
            <tr>
                <td class="info-label">Adresse / Zone</td>
                <td class="info-value">{{ $dossier->localisation ?? 'Non précisée' }}</td>
            </tr>
            <tr>
                <td class="info-label">Latitude (WGS 84)</td>
                <td class="info-value">{{ number_format($dossier->lat, 6) }}°</td>
            </tr>
            <tr>
                <td class="info-label">Longitude (WGS 84)</td>
                <td class="info-value">{{ number_format($dossier->lng, 6) }}°</td>
            </tr>
        </table>
    </div>
    @endif

    {{-- 4. CHECKLIST DOCUMENTS --}}
    <div class="section">
        <div class="section-title">4. Checklist des documents obligatoires</div>
        <ul class="checklist">
            @foreach($checklist as $item)
                @php
                    $isDone = false;
                    $keyword = explode(' ', $item)[0];
                    foreach($uploadedNames as $uname) {
                        if(stripos($uname, $keyword) !== false) { $isDone = true; break; }
                    }
                    if(!$isDone && in_array($item, $uploadedTypes)) $isDone = true;
                @endphp
                <li>
                    <span class="check-box {{ $isDone ? 'done' : '' }}">{{ $isDone ? '✓' : '' }}</span>
                    <span style="color:{{ $isDone ? '#15803d' : '#64748b' }};">{{ $item }}</span>
                    @if($isDone) <span style="color:#22c55e;margin-left:auto;font-size:9pt;"> ✓ Fourni</span> @endif
                </li>
            @endforeach
        </ul>
        <p style="margin-top:10px;font-size:10pt;color:#475569;">
            <strong>Avancement :</strong> {{ $doneCount }}/{{ count($checklist) }} documents fournis ({{ $pct }}%)
        </p>
    </div>

    {{-- 5. DOCUMENTS FOURNIS --}}
    @if($dossier->documents->isNotEmpty())
    <div class="section">
        <div class="section-title">5. Documents associés au dossier</div>
        <table class="doc-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom du document</th>
                    <th>Catégorie</th>
                    <th>Format</th>
                    <th>Date d'ajout</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dossier->documents as $i => $doc)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $doc->nom }}</strong></td>
                    <td>{{ $doc->type_document }}</td>
                    <td>{{ strtoupper($doc->extension ?? '?') }}</td>
                    <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- 6. RÉSUMÉ --}}
    <div class="section">
        <div class="section-title">6. Résumé et conclusions</div>
        <div class="summary-box">
            Le dossier <strong>#{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }}</strong>
            de type <strong>{{ $dossier->type_label }}</strong>, appartenant à
            <strong>{{ $dossier->proprietaire }}</strong>, est actuellement
            <strong>{{ strtolower($dossier->statut_label) }}</strong>.
            @if(count($checklist) > 0)
                {{ $doneCount }} sur {{ count($checklist) }} documents requis ont été fournis,
                représentant un taux de complétion de <strong>{{ $pct }}%</strong>.
            @endif
            @if($dossier->localisation)
                Ce dossier concerne un terrain situé à <strong>{{ $dossier->localisation }}</strong>.
            @endif
        </div>
    </div>

    {{-- 7. SIGNATURES --}}
    <div style="margin-top:30px;border-top:1px solid #e2e8f0;padding-top:20px;">
        <div class="section-title" style="margin-bottom:16px;">7. Signatures et visa</div>
        <div class="clearfix">
            <div class="sig-col">
                <div class="sig-label">Le Topographe responsable</div>
                <div class="sig-line"></div>
                <div class="sig-label">Nom : ____________________________</div>
                <div class="sig-label" style="margin-top:4px;">Date : ____________________________</div>
            </div>
            <div class="sig-col" style="margin-left:10%;">
                <div class="sig-label">Visa ANCFCC</div>
                <div class="sig-line"></div>
                <div class="sig-label">Cachet et signature</div>
            </div>
        </div>
    </div>

</div>
</body>
</html>
