<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PV de Bornage — {{ $dossier->proprietaire }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, serif;
            font-size: 10.5pt;
            color: #1e293b;
            background: #ffffff;
        }

        /* ── EN-TÊTE OFFICIELLE ── */
        .header {
            background: #1a3a6b;
            color: white;
            padding: 0;
            margin-bottom: 0;
        }
        .header-top {
            padding: 18px 30px 14px;
        }
        .header-logo { float: left; width: 60%; }
        .header-meta {
            float: right; width: 40%; text-align: right;
            font-size: 8.5pt; color: rgba(255,255,255,0.75);
        }
        .header-title {
            font-size: 20pt; font-weight: bold;
            color: #ffffff; margin-bottom: 2px; letter-spacing: 1px;
        }
        .header-subtitle {
            font-size: 9pt; color: rgba(255,255,255,0.65);
        }
        .clearfix::after { content: ''; display: table; clear: both; }

        .header-band {
            background: #c8a84b;
            padding: 8px 30px;
            font-size: 11pt; font-weight: bold;
            color: #1a3a6b; letter-spacing: 0.5px;
        }

        /* ── TITRE DOCUMENT ── */
        .doc-title-block {
            text-align: center;
            padding: 22px 30px 16px;
            border-bottom: 2px solid #1a3a6b;
            margin-bottom: 0;
        }
        .doc-title {
            font-size: 17pt; font-weight: bold;
            color: #1a3a6b; letter-spacing: 1px;
            text-transform: uppercase;
        }
        .doc-subtitle {
            font-size: 10pt; color: #475569; margin-top: 4px;
        }
        .doc-ref {
            display: inline-block;
            margin-top: 8px;
            padding: 4px 16px;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 9pt;
            color: #334155;
        }

        /* ── CONTENU ── */
        .content { padding: 18px 30px 30px; }

        .section { margin-bottom: 20px; }
        .section-title {
            font-size: 10.5pt; font-weight: bold;
            color: #1a3a6b; border-bottom: 2px solid #c8a84b;
            padding-bottom: 5px; margin-bottom: 12px;
            text-transform: uppercase; letter-spacing: 0.5px;
        }

        /* Info grid */
        .info-grid { width: 100%; border-collapse: collapse; }
        .info-grid td {
            padding: 7px 10px; font-size: 10pt;
            vertical-align: top;
        }
        .info-grid tr:nth-child(even) td { background: #f8fafc; }
        .info-label {
            font-weight: bold; color: #475569;
            width: 35%; border-right: 1px solid #e2e8f0;
        }
        .info-value { color: #1e293b; }

        /* Coordonnées */
        .coords-table { width: 100%; border-collapse: collapse; font-size: 10pt; }
        .coords-table th {
            background: #1a3a6b; color: white;
            padding: 8px 12px; text-align: center; font-size: 9.5pt;
        }
        .coords-table td {
            padding: 8px 12px; text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .coords-table tr:nth-child(even) td { background: #f8fafc; }

        /* Témoins */
        .temoin-block {
            border: 1px solid #e2e8f0; border-radius: 4px;
            padding: 12px; margin-bottom: 12px; background: #fafbfc;
        }
        .temoin-line {
            border-bottom: 1px solid #cbd5e1;
            height: 24px; margin-bottom: 6px;
            position: relative;
        }
        .temoin-label {
            font-size: 8.5pt; color: #64748b;
        }

        /* Signatures */
        .sig-grid { width: 100%; }
        .sig-col { float: left; width: 28%; margin-right: 5%; }
        .sig-col:last-child { margin-right: 0; }
        .sig-title {
            font-size: 9pt; font-weight: bold; color: #1a3a6b;
            text-align: center; margin-bottom: 6px;
            border-bottom: 1px solid #cbd5e1; padding-bottom: 4px;
        }
        .sig-line {
            border-bottom: 1px solid #475569;
            height: 50px; margin: 8px 0 4px;
        }
        .sig-label { font-size: 8pt; color: #64748b; text-align: center; }

        /* Cachet */
        .cachet-box {
            border: 2px dashed #cbd5e1;
            height: 80px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }

        /* Footer */
        .footer {
            position: fixed; bottom: 0; left: 0; right: 0;
            padding: 7px 30px;
            background: #f8fafc;
            border-top: 2px solid #1a3a6b;
            font-size: 7.5pt; color: #94a3b8;
        }
        .footer-left  { float: left; }
        .footer-right { float: right; }

        /* Bandeau rouge attention */
        .notice-box {
            background: #fef9ed; border: 1px solid #f59e0b;
            border-left: 4px solid #f59e0b;
            padding: 10px 14px; border-radius: 4px;
            font-size: 9.5pt; color: #78350f;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

{{-- FOOTER --}}
<div class="footer">
    <div class="footer-left">
        ANCFCC — Agence Nationale de la Conservation Foncière, du Cadastre et de la Cartographie &bull; MAROC
    </div>
    <div class="footer-right">
        PV de Bornage #{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }} &bull; Généré le {{ now()->format('d/m/Y à H:i') }}
    </div>
    <div class="clearfix"></div>
</div>

{{-- EN-TÊTE --}}
<div class="header">
    <div class="header-top clearfix">
        <div class="header-logo">
            <div class="header-title">TOPOSMART</div>
            <div class="header-subtitle">Plateforme Intelligente de Gestion des Dossiers Topographiques</div>
        </div>
        <div class="header-meta">
            <strong>ANCFCC — ROYAUME DU MAROC</strong><br>
            Dossier N° : {{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }}<br>
            Date : {{ now()->format('d/m/Y') }}<br>
            Type : {{ strtoupper($dossier->type_label) }}
        </div>
    </div>
    <div class="header-band">
        PROCÈS-VERBAL DE BORNAGE — OPÉRATION TOPOGRAPHIQUE OFFICIELLE
    </div>
</div>

{{-- TITRE DOCUMENT --}}
<div class="doc-title-block">
    <div class="doc-title">Procès-Verbal de Bornage</div>
    <div class="doc-subtitle">Dossier de type : {{ $dossier->type_label }}</div>
    <div class="doc-ref">
        Réf : PV-{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }} &nbsp;|&nbsp;
        Établi le {{ $dossier->date_creation->format('d/m/Y') }}
    </div>
</div>

<div class="content">

    {{-- 1. IDENTITÉ DU PROPRIÉTAIRE --}}
    <div class="section">
        <div class="section-title">I. Identité du Propriétaire</div>
        <table class="info-grid">
            <tr>
                <td class="info-label">Nom complet du propriétaire</td>
                <td class="info-value"><strong>{{ strtoupper($dossier->proprietaire) }}</strong></td>
            </tr>
            <tr>
                <td class="info-label">Type de dossier</td>
                <td class="info-value">{{ $dossier->type_label }}</td>
            </tr>
            <tr>
                <td class="info-label">Date du bornage</td>
                <td class="info-value">{{ $dossier->date_creation->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="info-label">Localisation du terrain</td>
                <td class="info-value">{{ $dossier->localisation ?? '— Non précisée —' }}</td>
            </tr>
            <tr>
                <td class="info-label">Statut du dossier</td>
                <td class="info-value">{{ $dossier->statut_label }}</td>
            </tr>
            <tr>
                <td class="info-label">Topographe responsable</td>
                <td class="info-value">{{ $dossier->user->name ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    {{-- 2. DESCRIPTION DES OPÉRATIONS --}}
    <div class="section">
        <div class="section-title">II. Description des Opérations de Bornage</div>
        @if($dossier->description)
            <p style="font-size:10pt;line-height:1.75;color:#334155;padding:12px;background:#f8fafc;border-left:4px solid #1a3a6b;border-radius:2px;">
                {{ $dossier->description }}
            </p>
        @else
            <div class="notice-box">
                Attention : Aucune description des opérations n'a été renseignée pour ce dossier.
            </div>
        @endif

        <p style="margin-top:12px;font-size:10pt;color:#475569;">
            Nous soussignés, certifions avoir procédé aux opérations de bornage du terrain appartenant
            à <strong>{{ $dossier->proprietaire }}</strong>, situé à <strong>{{ $dossier->localisation ?? 'localisation non précisée' }}</strong>,
            en respectant les procédures et normes en vigueur de l'ANCFCC.
        </p>
    </div>

    {{-- 3. COORDONNÉES GÉOGRAPHIQUES --}}
    <div class="section">
        <div class="section-title">III. Coordonnées Géographiques des Bornes</div>

        @php
            $bornes = $dossier->bornes ?? [];

            // Si des bornes multiples existent, les utiliser
            // Sinon, utiliser lat/lng principal comme borne 1
            if (empty($bornes) && $dossier->lat && $dossier->lng) {
                $bornes = [[
                    'label' => 'B-01',
                    'lat'   => $dossier->lat,
                    'lng'   => $dossier->lng,
                    'note'  => 'Borne principale (position GPS du dossier)',
                ]];
            }
        @endphp

        <table class="coords-table">
            <thead>
                <tr>
                    <th>Borne N°</th>
                    <th>Latitude (Y)</th>
                    <th>Longitude (X)</th>
                    <th>Système de Référence</th>
                    <th>Observations</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($bornes))
                    @foreach($bornes as $i => $borne)
                    <tr>
                        <td><strong>{{ $borne['label'] ?? 'B-' . str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>{{ number_format((float)$borne['lat'], 6) }}°</td>
                        <td>{{ number_format((float)$borne['lng'], 6) }}°</td>
                        <td>WGS 84 / Lambert Maroc</td>
                        <td>{{ $borne['note'] ?? '' }}</td>
                    </tr>
                    @endforeach
                    {{-- Lignes vides pour signature terrain (min 4 lignes total) --}}
                    @for($i = count($bornes); $i < 4; $i++)
                    <tr>
                        <td style="color:#94a3b8;">B-{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</td>
                        <td style="color:#94a3b8;" colspan="4">À compléter sur le terrain</td>
                    </tr>
                    @endfor
                @else
                    @for($i = 0; $i < 4; $i++)
                    <tr>
                        <td>B-{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</td>
                        <td>________________________</td>
                        <td>________________________</td>
                        <td>WGS 84 / Lambert Maroc</td>
                        <td></td>
                    </tr>
                    @endfor
                @endif
            </tbody>
        </table>

        @if(!empty($bornes))
        <p style="margin-top:8px;font-size:8.5pt;color:#64748b;">
            * {{ count($bornes) }} borne(s) enregistrée(s) dans le système numérique. Précision ± 0.5 m.
        </p>
        @else
        <p style="margin-top:8px;font-size:8.5pt;color:#64748b;">
            * Coordonnées à renseigner après les opérations de terrain. Précision ± 0.5 m.
        </p>
        @endif
    </div>

    {{-- 4. DÉCLARATION DES TÉMOINS / RIVERAINS --}}
    <div class="section">
        <div class="section-title">IV. Déclaration des Riverains et Témoins</div>
        <p style="font-size:9.5pt;color:#475569;margin-bottom:12px;">
            Les riverains et propriétaires voisins ci-dessous ont été convoqués et ont assisté aux opérations de bornage :
        </p>

        <div class="temoin-block">
            <p style="font-size:9pt;color:#475569;margin-bottom:4px;"><strong>Riverain 1 :</strong></p>
            <div class="temoin-line"></div>
            <div style="display:flex;gap:30px;">
                <div style="flex:1;">
                    <span class="temoin-label">Nom et prénom : ________________________</span>
                </div>
                <div style="flex:1;">
                    <span class="temoin-label">CIN : ________________</span>
                </div>
                <div style="flex:1;">
                    <span class="temoin-label">Signature : ________________</span>
                </div>
            </div>
        </div>

        <div class="temoin-block">
            <p style="font-size:9pt;color:#475569;margin-bottom:4px;"><strong>Riverain 2 :</strong></p>
            <div class="temoin-line"></div>
            <div style="display:flex;gap:30px;">
                <div style="flex:1;">
                    <span class="temoin-label">Nom et prénom : ________________________</span>
                </div>
                <div style="flex:1;">
                    <span class="temoin-label">CIN : ________________</span>
                </div>
                <div style="flex:1;">
                    <span class="temoin-label">Signature : ________________</span>
                </div>
            </div>
        </div>

        <div class="temoin-block">
            <p style="font-size:9pt;color:#475569;margin-bottom:4px;"><strong>Riverain 3 :</strong></p>
            <div class="temoin-line"></div>
            <div style="display:flex;gap:30px;">
                <div style="flex:1;">
                    <span class="temoin-label">Nom et prénom : ________________________</span>
                </div>
                <div style="flex:1;">
                    <span class="temoin-label">CIN : ________________</span>
                </div>
                <div style="flex:1;">
                    <span class="temoin-label">Signature : ________________</span>
                </div>
            </div>
        </div>

        <p style="font-size:9pt;color:#475569;margin-top:10px;">
            <strong>Déclaration :</strong> Les parties soussignées déclarent ne pas avoir d'opposition aux limites définies
            lors de cette opération de bornage, conformément au dahir portant loi n° 1-11-177 du 22 novembre 2011.
        </p>
    </div>

    {{-- 5. SIGNATURES OFFICIELLES --}}
    <div class="section">
        <div class="section-title">V. Signatures et Visa Officiels</div>
        <div class="clearfix">
            <div class="sig-col">
                <div class="sig-title">Le Propriétaire</div>
                <div class="sig-line"></div>
                <div class="sig-label">{{ $dossier->proprietaire }}</div>
                <div class="sig-label" style="margin-top:3px;">Date : ____________________</div>
            </div>
            <div class="sig-col">
                <div class="sig-title">Le Topographe</div>
                <div class="sig-line"></div>
                <div class="sig-label">{{ $dossier->user->name ?? '_______________' }}</div>
                <div class="sig-label" style="margin-top:3px;">N° Ordre : ____________________</div>
            </div>
            <div class="sig-col">
                <div class="sig-title">Visa ANCFCC</div>
                <div class="cachet-box">
                    <span style="font-size:8pt;color:#94a3b8;">Cachet officiel</span>
                </div>
                <div class="sig-label" style="margin-top:4px;">Chef de la Conservation</div>
            </div>
        </div>
    </div>

</div>
</body>
</html>
