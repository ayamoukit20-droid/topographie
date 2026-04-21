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
        .header-top {
            display: flex; /* DomPDF use float instead */
        }
        .header-logo {
            float: left;
            width: 60%;
        }
        .header-meta {
            float: right;
            width: 40%;
            text-align: right;
            font-size: 9pt;
            color: rgba(255,255,255,0.7);
        }
        .header-title {
            font-size: 18pt;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 3px;
        }
        .header-subtitle {
            font-size: 10pt;
            color: rgba(255,255,255,0.65);
        }
        .clearfix::after { content: ''; display: table; clear: both; }

        /* ── BANDE BLEUE ── */
        .blue-band {
            background: #2563eb;
            color: white;
            padding: 10px 30px;
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 24px;
        }

        /* ── CONTENU ── */
        .content { padding: 0 30px 30px; }

        .section {
            margin-bottom: 22px;
        }
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            color: #1a56b0;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 5px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Info grid */
        .info-grid { width: 100%; }
        .info-grid td {
            padding: 7px 10px;
            font-size: 10pt;
            vertical-align: top;
        }
        .info-grid tr:nth-child(even) td { background: #f8fafc; }
        .info-label {
            font-weight: bold;
            color: #475569;
            width: 35%;
        }
        .info-value { color: #1e293b; }

        /* Badge statut */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 9pt;
            font-weight: bold;
        }
        .badge-warning  { background: #fef3c7; color: #92400e; border: 1px solid #f59e0b; }
        .badge-info     { background: #dbeafe; color: #1e40af; border: 1px solid #3b82f6; }
        .badge-success  { background: #dcfce7; color: #14532d; border: 1px solid #22c55e; }

        /* Checklist */
        .checklist { list-style: none; padding: 0; }
        .checklist li {
            padding: 7px 12px;
            font-size: 10pt;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
        }
        .checklist li:last-child { border-bottom: none; }
        .check-box {
            display: inline-block;
            width: 16px; height: 16px;
            border: 2px solid #cbd5e1;
            border-radius: 3px;
            margin-right: 10px;
            margin-top: 1px;
            flex-shrink: 0;
        }
        .check-box.done {
            background: #22c55e;
            border-color: #22c55e;
            color: white;
            text-align: center;
            line-height: 12px;
            font-size: 9pt;
        }

        /* Documents table */
        .doc-table { width: 100%; border-collapse: collapse; font-size: 10pt; }
        .doc-table th {
            background: #1e40af;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
        }
        .doc-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #1e293b;
        }
        .doc-table tr:nth-child(even) td { background: #f8fafc; }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 8px 30px;
            background: #f8fafc;
            border-top: 2px solid #e2e8f0;
            font-size: 8pt;
            color: #94a3b8;
        }
        .footer-left  { float: left; }
        .footer-right { float: right; }

        /* Signature block */
        .signature-block {
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
        .sig-col { float: left; width: 45%; }
        .sig-line {
            border-bottom: 1px solid #475569;
            height: 40px;
            margin: 10px 0 5px;
        }
        .sig-label { font-size: 9pt; color: #64748b; }
    </style>
</head>
<body>

{{-- FOOTER (fixe sur chaque page) --}}
<div class="footer">
    <div class="footer-left">
        ANCFCC — Agence Nationale de la Conservation Fonciere, du Cadastre et de la Cartographie
    </div>
    <div class="footer-right">
        Genere le {{ now()->format('d/m/Y a H:i') }} &bull; Page <span class="pagenum"></span>
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

    {{-- 1. INFORMATIONS GENERALES --}}
    <div class="section">
        <div class="section-title">1. Informations generales du dossier</div>
        <table class="info-grid">
            <tr>
                <td class="info-label">Reference dossier</td>
                <td class="info-value">#{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td class="info-label">Type de dossier</td>
                <td class="info-value">{{ $dossier->type_label }}</td>
            </tr>
            <tr>
                <td class="info-label">Proprietaire</td>
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
                <td class="info-label">Date de creation</td>
                <td class="info-value">{{ $dossier->date_creation->format('d/m/Y') }}</td>
                <td class="info-label">Cree par</td>
                <td class="info-value">{{ $dossier->user->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="info-label">Localisation</td>
                <td class="info-value" colspan="3">{{ $dossier->localisation ?? '—' }}</td>
            </tr>
            @if($dossier->lat && $dossier->lng)
            <tr>
                <td class="info-label">Coordonnees GPS</td>
                <td class="info-value" colspan="3">
                    Lat: {{ $dossier->lat }} | Lng: {{ $dossier->lng }}
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

    {{-- 3. CHECKLIST DOCUMENTS --}}
    <div class="section">
        <div class="section-title">3. Checklist des documents obligatoires</div>
        @php
            $checklist = $dossier->checklist;
            $uploadedNames = $dossier->documents->pluck('nom')->toArray();
        @endphp
        <ul class="checklist">
            @foreach($checklist as $item)
                @php
                    $isDone = false;
                    $keyword = explode(' ', $item)[0];
                    foreach($uploadedNames as $uname) {
                        if(stripos($uname, $keyword) !== false) { $isDone = true; break; }
                    }
                @endphp
                <li>
                    <span class="check-box {{ $isDone ? 'done' : '' }}">{{ $isDone ? '✓' : '' }}</span>
                    <span style="color:{{ $isDone ? '#15803d' : '#64748b' }};">{{ $item }}</span>
                    @if($isDone) <span style="color:#22c55e;margin-left:auto;font-size:9pt;"> Fourni</span> @endif
                </li>
            @endforeach
        </ul>
        @php
            $doneCount = collect($checklist)->filter(function($item) use ($uploadedNames) {
                $keyword = explode(' ', $item)[0];
                foreach($uploadedNames as $uname) {
                    if(stripos($uname, $keyword) !== false) return true;
                }
                return false;
            })->count();
            $pct = count($checklist) > 0 ? round(($doneCount / count($checklist)) * 100) : 0;
        @endphp
        <p style="margin-top:10px;font-size:10pt;color:#475569;">
            <strong>Avancement :</strong> {{ $doneCount }}/{{ count($checklist) }} documents fournis ({{ $pct }}%)
        </p>
    </div>

    {{-- 4. DOCUMENTS FOURNIS --}}
    @if($dossier->documents->isNotEmpty())
    <div class="section">
        <div class="section-title">4. Documents fournis</div>
        <table class="doc-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom du document</th>
                    <th>Type</th>
                    <th>Format</th>
                    <th>Date</th>
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

    {{-- 5. SIGNATURES --}}
    <div class="signature-block">
        <div class="section-title" style="margin-bottom:16px;">5. Signatures et visa</div>
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
