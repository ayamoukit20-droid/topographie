@extends('layouts.app')

@section('title', 'Outils Topographiques — TopoSmart')
@section('page-title', 'Outils de calcul topographique')

@push('styles')
<style>
/* ── TABS ── */
.tool-tabs {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
    margin-bottom: 24px;
    background: rgba(255,255,255,0.03);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 6px;
}
.tool-tab {
    flex: 1;
    min-width: 120px;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    background: transparent;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    white-space: nowrap;
}
.tool-tab.active,
.tool-tab:hover { background: var(--blue-400); color: #fff; }
.tool-panel { display: none; }
.tool-panel.active { display: block; animation: fadeIn 0.3s ease; }
@keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:none} }

/* ── RESULT BOX ── */
.result-box {
    background: rgba(37,99,235,0.06);
    border: 1px solid rgba(59,130,246,0.25);
    border-radius: 12px;
    padding: 20px;
    margin-top: 20px;
}
.result-label { font-size: 13px; color: var(--text-muted); }
.result-value { font-size: 20px; font-weight: 700; color: #60a5fa; }
.result-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
}
.result-row:last-child { border-bottom: none; }

/* ── POINT ROWS ── */
.point-row {
    display: grid;
    grid-template-columns: 36px 1fr 1fr 36px;
    gap: 10px;
    align-items: center;
    background: rgba(255,255,255,0.04);
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid var(--border);
}
.point-num { font-size:12px; color:#60a5fa; font-weight:700; text-align:center; }
.del-btn { background:none; border:none; color:#ef4444; cursor:pointer; font-size:15px; text-align:center; }
.add-row-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px;
    background: rgba(37,99,235,0.1);
    border: 1px dashed rgba(59,130,246,0.4);
    border-radius: 8px;
    color: #60a5fa;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
    font-weight: 500;
}
.add-row-btn:hover { background: rgba(37,99,235,0.2); }

/* ── INFO CARD ── */
.info-card {
    background: rgba(37,99,235,0.06);
    border: 1px solid rgba(59,130,246,0.2);
    border-left: 3px solid var(--blue-400);
    border-radius: 10px;
    padding: 16px;
}
.info-card h6 { color:#60a5fa; font-size:13px; font-weight:700; margin-bottom:10px; }
.info-card p { font-size:12.5px; color:var(--text-muted); line-height:1.8; margin:0; }

/* ── QUICK TABLE ── */
.quick-table { width:100%; font-size:12.5px; border-collapse:collapse; }
.quick-table th { padding:8px 0; font-weight:600; color:var(--text-muted); border-bottom:2px solid var(--border); }
.quick-table td { padding:7px 0; border-bottom:1px solid var(--border); }
.quick-table td:last-child { text-align:right; color:#60a5fa; font-weight:600; }
</style>
@endpush

@section('content')
<div class="breadcrumb-topo">
    <a href="{{ route('dashboard') }}">Accueil</a>
    <span class="sep">/</span>
    <span>Outils topographiques</span>
</div>

{{-- TABS --}}
<div class="tool-tabs">
    <button class="tool-tab active" onclick="showTool('distance', this)">
        <i class="bi bi-rulers"></i> Distance
    </button>
    <button class="tool-tab" onclick="showTool('surface', this)">
        <i class="bi bi-pentagon"></i> Surface
    </button>
    <button class="tool-tab" onclick="showTool('gisement', this)">
        <i class="bi bi-compass"></i> Gisement
    </button>
    <button class="tool-tab" onclick="showTool('emprise', this)">
        <i class="bi bi-house-door"></i> Emprise sol
    </button>
    <button class="tool-tab" onclick="showTool('tantiemes', this)">
        <i class="bi bi-building"></i> Tantiemes
    </button>
    <button class="tool-tab" onclick="showTool('conversion', this)">
        <i class="bi bi-arrow-left-right"></i> Conversion
    </button>
</div>

{{-- ═══════════════════════════
     OUTIL 1 — DISTANCE
════════════════════════════ --}}
<div class="tool-panel active" id="panel-distance">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card-topo">
                <h6 style="font-size:15px;font-weight:700;margin-bottom:6px;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-rulers" style="color:#60a5fa;"></i> Distance entre 2 points
                </h6>
                <p style="font-size:12.5px;color:var(--text-muted);margin-bottom:22px;">
                    D = &radic;((X₂−X₁)² + (Y₂−Y₁)²)
                </p>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label-topo">Point 1 — X (m)</label>
                        <input type="number" id="d_x1" class="form-control-topo" placeholder="0.000" step="any">
                    </div>
                    <div class="col-6">
                        <label class="form-label-topo">Point 1 — Y (m)</label>
                        <input type="number" id="d_y1" class="form-control-topo" placeholder="0.000" step="any">
                    </div>
                    <div class="col-6">
                        <label class="form-label-topo">Point 2 — X (m)</label>
                        <input type="number" id="d_x2" class="form-control-topo" placeholder="0.000" step="any">
                    </div>
                    <div class="col-6">
                        <label class="form-label-topo">Point 2 — Y (m)</label>
                        <input type="number" id="d_y2" class="form-control-topo" placeholder="0.000" step="any">
                    </div>
                </div>
                <button class="btn-orange mt-4" onclick="calculerDistance()">
                    <i class="bi bi-calculator"></i> Calculer
                </button>
                <div class="result-box" id="res-distance" style="display:none;">
                    <div style="font-size:11px;color:#60a5fa;font-weight:700;letter-spacing:1px;margin-bottom:12px;">RESULTAT</div>
                    <div class="result-row">
                        <span class="result-label">Distance (metres)</span>
                        <span class="result-value" id="r-dm">—</span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Distance (kilometres)</span>
                        <span class="result-value" style="font-size:14px;" id="r-dkm">—</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="info-card">
                <h6><i class="bi bi-info-circle"></i> Comment utiliser</h6>
                <p>Entrez les coordonnees planes <strong style="color:rgba(255,255,255,0.7);">(X, Y)</strong> des deux points dans le meme systeme de projection (Lambert ou UTM).<br><br>
                La formule calcule la <strong style="color:rgba(255,255,255,0.7);">distance euclidienne</strong> en metres.</p>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════
     OUTIL 2 — SURFACE (GAUSS)
════════════════════════════ --}}
<div class="tool-panel" id="panel-surface">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card-topo">
                <h6 style="font-size:15px;font-weight:700;margin-bottom:6px;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-pentagon" style="color:#60a5fa;"></i> Surface de polygone (methode de Gauss)
                </h6>
                <p style="font-size:12.5px;color:var(--text-muted);margin-bottom:22px;">
                    Formule du lacet / Shoelace — Minimum 3 points
                </p>
                <div id="surface-points" style="display:flex;flex-direction:column;gap:8px;margin-bottom:12px;"></div>
                <button class="add-row-btn" onclick="ajouterPointSurface()">
                    <i class="bi bi-plus-circle"></i> Ajouter un sommet
                </button>
                <br><br>
                <button class="btn-orange" onclick="calculerSurface()">
                    <i class="bi bi-calculator"></i> Calculer la surface
                </button>
                <div class="result-box" id="res-surface" style="display:none;">
                    <div style="font-size:11px;color:#60a5fa;font-weight:700;letter-spacing:1px;margin-bottom:12px;">RESULTATS</div>
                    <div class="result-row"><span class="result-label">Surface (m²)</span><span class="result-value" id="r-sm2">—</span></div>
                    <div class="result-row"><span class="result-label">Surface (ares)</span><span class="result-value" style="font-size:14px;" id="r-sar">—</span></div>
                    <div class="result-row"><span class="result-label">Surface (hectares)</span><span class="result-value" style="font-size:14px;" id="r-sha">—</span></div>
                    <div class="result-row"><span class="result-label">Perimetre</span><span class="result-value" style="font-size:14px;" id="r-sp">—</span></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="info-card">
                <h6><i class="bi bi-info-circle"></i> Methode de Gauss</h6>
                <p>Entrez les sommets du polygone (parcelle) <strong style="color:rgba(255,255,255,0.7);">dans l'ordre</strong>, sens horaire ou antihoraire.<br><br>
                Le polygone est ferme automatiquement par la formule.<br><br>
                Minimum <strong style="color:rgba(255,255,255,0.7);">3 points</strong> requis.</p>
            </div>
            <div class="info-card mt-3">
                <h6><i class="bi bi-table"></i> Equivalences</h6>
                <table class="quick-table">
                    <tr><td>1 hectare</td><td>10 000 m²</td></tr>
                    <tr><td>1 are</td><td>100 m²</td></tr>
                    <tr><td>1 hectare</td><td>100 ares</td></tr>
                    <tr><td>1 are</td><td>100 ca</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════
     OUTIL 3 — GISEMENT
════════════════════════════ --}}
<div class="tool-panel" id="panel-gisement">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card-topo">
                <h6 style="font-size:15px;font-weight:700;margin-bottom:6px;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-compass" style="color:#60a5fa;"></i> Calcul du gisement
                </h6>
                <p style="font-size:12.5px;color:var(--text-muted);margin-bottom:22px;">
                    G = arctan((X₂−X₁) / (Y₂−Y₁)) ramene dans [0°, 360°]
                </p>
                <div class="row g-3">
                    <div class="col-6"><label class="form-label-topo">Point A — X (m)</label><input type="number" id="g_x1" class="form-control-topo" placeholder="0.000" step="any"></div>
                    <div class="col-6"><label class="form-label-topo">Point A — Y (m)</label><input type="number" id="g_y1" class="form-control-topo" placeholder="0.000" step="any"></div>
                    <div class="col-6"><label class="form-label-topo">Point B — X (m)</label><input type="number" id="g_x2" class="form-control-topo" placeholder="0.000" step="any"></div>
                    <div class="col-6"><label class="form-label-topo">Point B — Y (m)</label><input type="number" id="g_y2" class="form-control-topo" placeholder="0.000" step="any"></div>
                </div>
                <button class="btn-orange mt-4" onclick="calculerGisement()">
                    <i class="bi bi-compass"></i> Calculer le gisement
                </button>
                <div class="result-box" id="res-gisement" style="display:none;">
                    <div style="font-size:11px;color:#60a5fa;font-weight:700;letter-spacing:1px;margin-bottom:12px;">RESULTAT</div>
                    <div class="result-row"><span class="result-label">Gisement (degres decimaux)</span><span class="result-value" id="r-gdeg">—</span></div>
                    <div class="result-row"><span class="result-label">Gisement (D°M'S'')</span><span class="result-value" style="font-size:14px;" id="r-gdms">—</span></div>
                    <div class="result-row"><span class="result-label">Gisement (grades)</span><span class="result-value" style="font-size:14px;" id="r-ggrad">—</span></div>
                    <div class="result-row"><span class="result-label">Distance AB</span><span class="result-value" style="font-size:14px;" id="r-gdist">—</span></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="info-card">
                <h6><i class="bi bi-compass"></i> Definition du gisement</h6>
                <p>Le <strong style="color:rgba(255,255,255,0.7);">gisement</strong> est l'angle mesure depuis le <strong style="color:rgba(255,255,255,0.7);">Nord geographique</strong>, dans le sens des aiguilles d'une montre, jusqu'a la direction AB.<br><br>
                Valeur comprise entre <strong style="color:rgba(255,255,255,0.7);">0° et 360°</strong>.<br><br>
                Utilise pour l'implantation et la verification des alignements.</p>
            </div>
            <div class="info-card mt-3">
                <h6><i class="bi bi-table"></i> Correspondances angulaires</h6>
                <table class="quick-table">
                    <tr><td>360°</td><td>400 gon (grades)</td></tr>
                    <tr><td>90°</td><td>100 gon</td></tr>
                    <tr><td>1° = 60'</td><td>1' = 60''</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════
     OUTIL 4 — EMPRISE AU SOL
════════════════════════════ --}}
<div class="tool-panel" id="panel-emprise">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card-topo">
                <h6 style="font-size:15px;font-weight:700;margin-bottom:6px;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-house-door" style="color:#60a5fa;"></i> Calcul de l'emprise au sol
                </h6>
                <p style="font-size:12.5px;color:var(--text-muted);margin-bottom:22px;">
                    CES = Surface batiment / Surface parcelle &times; 100
                </p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-topo">Surface de la parcelle (m²) *</label>
                        <input type="number" id="e_parcelle" class="form-control-topo" placeholder="Ex: 500" step="any" min="0.01">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-topo">Surface batiment au sol (m²) *</label>
                        <input type="number" id="e_batiment" class="form-control-topo" placeholder="Ex: 120" step="any" min="0.01">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-topo">Nombre d'etages</label>
                        <input type="number" id="e_etages" class="form-control-topo" placeholder="Ex: 3" step="1" min="1" value="1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-topo">Surface plancher / etage (m²)</label>
                        <input type="number" id="e_plancher" class="form-control-topo" placeholder="Ex: 100" step="any" min="0">
                    </div>
                </div>
                <button class="btn-orange mt-4" onclick="calculerEmprise()">
                    <i class="bi bi-calculator"></i> Calculer
                </button>
                <div class="result-box" id="res-emprise" style="display:none;">
                    <div style="font-size:11px;color:#60a5fa;font-weight:700;letter-spacing:1px;margin-bottom:12px;">RESULTATS</div>
                    <div class="result-row"><span class="result-label">Coefficient d'Emprise au Sol (CES)</span><span class="result-value" id="r-ces">—</span></div>
                    <div class="result-row"><span class="result-label">Surface libre de la parcelle</span><span class="result-value" style="font-size:14px;" id="r-libre">—</span></div>
                    <div class="result-row"><span class="result-label">Surface de plancher totale (SHON)</span><span class="result-value" style="font-size:14px;" id="r-shon">—</span></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="info-card">
                <h6><i class="bi bi-info-circle"></i> Coefficient d'Emprise au Sol</h6>
                <p>Le <strong style="color:rgba(255,255,255,0.7);">CES</strong> est le rapport entre la surface au sol construite et la surface totale de la parcelle.<br><br>
                Il est defini par le reglement d'urbanisme et ne peut pas etre depasse.<br><br>
                <strong style="color:#4ade80;">CES &le; 40%</strong> — Generalement autorise<br>
                <strong style="color:#fbbf24;">CES &gt; 60%</strong> — Necessite autorisation speciale</p>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════
     OUTIL 5 — TANTIEMES
════════════════════════════ --}}
<div class="tool-panel" id="panel-tantiemes">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-topo">
                <h6 style="font-size:15px;font-weight:700;margin-bottom:6px;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-building" style="color:#60a5fa;"></i> Calcul des tantiemes — Copropriete
                </h6>
                <p style="font-size:12.5px;color:var(--text-muted);margin-bottom:20px;">
                    Tantiemes = (Surface lot / Surface totale) &times; Base (milliemes)
                </p>
                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <label class="form-label-topo">Base de calcul</label>
                        <select id="t_base" class="form-select-topo">
                            <option value="1000">1 000 milliemes</option>
                            <option value="10000">10 000 dix-milliemes</option>
                            <option value="100">100 centiemes</option>
                        </select>
                    </div>
                    <div class="col-md-8 d-flex align-items-end">
                        <button class="add-row-btn" onclick="ajouterLot()">
                            <i class="bi bi-plus-circle"></i> Ajouter un lot
                        </button>
                    </div>
                </div>
                <div id="lots-list" style="display:flex;flex-direction:column;gap:8px;margin-bottom:16px;"></div>
                <button class="btn-orange" onclick="calculerTantiemes()">
                    <i class="bi bi-calculator"></i> Calculer les tantiemes
                </button>
                <div class="result-box" id="res-tantiemes" style="display:none;">
                    <div style="font-size:11px;color:#60a5fa;font-weight:700;letter-spacing:1px;margin-bottom:14px;display:flex;justify-content:space-between;">
                        <span>TABLEAU DES TANTIEMES</span>
                        <span id="t-info" style="color:var(--text-muted);font-weight:400;"></span>
                    </div>
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:13px;">
                            <thead>
                                <tr style="border-bottom:2px solid rgba(59,130,246,0.3);">
                                    <th style="padding:8px;text-align:left;color:var(--text-muted);font-size:11px;text-transform:uppercase;">Lot</th>
                                    <th style="padding:8px;text-align:right;color:var(--text-muted);font-size:11px;text-transform:uppercase;">Surface m²</th>
                                    <th style="padding:8px;text-align:right;color:var(--text-muted);font-size:11px;text-transform:uppercase;">%</th>
                                    <th style="padding:8px;text-align:right;color:#60a5fa;font-size:11px;text-transform:uppercase;">Tantiemes</th>
                                </tr>
                            </thead>
                            <tbody id="tantiemes-tbody"></tbody>
                            <tfoot>
                                <tr style="border-top:2px solid rgba(59,130,246,0.3);">
                                    <td style="padding:10px;font-weight:700;color:var(--white);">TOTAL</td>
                                    <td style="padding:10px;text-align:right;color:var(--white);font-weight:700;" id="t-total-surf"></td>
                                    <td style="padding:10px;text-align:right;color:#60a5fa;font-weight:700;">100%</td>
                                    <td style="padding:10px;text-align:right;color:#60a5fa;font-weight:700;" id="t-total"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="info-card">
                <h6><i class="bi bi-building"></i> Principe des tantiemes</h6>
                <p>Chaque lot se voit attribuer un nombre de <strong style="color:rgba(255,255,255,0.7);">milliemes</strong> proportionne a sa surface privative.<br><br>
                    Ces milliemes determinent la <strong style="color:rgba(255,255,255,0.7);">quote-part des charges</strong> communes.<br><br>
                    <strong style="color:rgba(255,255,255,0.7);">Tableau A</strong> → surfaces<br>
                    <strong style="color:rgba(255,255,255,0.7);">Tableau B</strong> → tantiemes</p>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════
     OUTIL 6 — CONVERSION
════════════════════════════ --}}
<div class="tool-panel" id="panel-conversion">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card-topo">
                <h6 style="font-size:15px;font-weight:700;margin-bottom:20px;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-arrow-left-right" style="color:#60a5fa;"></i> Conversion d'unites
                </h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label-topo">Valeur</label>
                        <input type="number" id="c_valeur" class="form-control-topo" placeholder="0" step="any">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-topo">De</label>
                        <select id="c_de" class="form-select-topo" onchange="updateToOptions()">
                            <optgroup label="Surface">
                                <option value="m2">Metre carre (m²)</option>
                                <option value="ha">Hectare (ha)</option>
                                <option value="ar">Are (a)</option>
                                <option value="ca">Centiare (ca)</option>
                            </optgroup>
                            <optgroup label="Longueur">
                                <option value="m">Metre (m)</option>
                                <option value="km">Kilometre (km)</option>
                                <option value="cm">Centimetre (cm)</option>
                                <option value="mm">Millimetre (mm)</option>
                                <option value="pied">Pied</option>
                                <option value="pouce">Pouce</option>
                            </optgroup>
                            <optgroup label="Angle">
                                <option value="deg">Degre (°)</option>
                                <option value="rad">Radian (rad)</option>
                                <option value="grad">Grade/Gon (g)</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-topo">Vers</label>
                        <select id="c_vers" class="form-select-topo"></select>
                    </div>
                </div>
                <button class="btn-orange mt-4" onclick="convertirUnites()">
                    <i class="bi bi-arrow-repeat"></i> Convertir
                </button>
                <div class="result-box" id="res-conversion" style="display:none;">
                    <div style="text-align:center;padding:16px 0;">
                        <div id="r-conv-from" style="font-size:14px;color:var(--text-muted);margin-bottom:8px;"></div>
                        <div id="r-conv-val" style="font-size:32px;color:#60a5fa;font-weight:800;"></div>
                        <div id="r-conv-to" style="font-size:14px;color:var(--text-muted);margin-top:8px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="info-card">
                <h6><i class="bi bi-table"></i> Tableau de reference</h6>
                <table class="quick-table">
                    <thead><tr><th>Surface</th><th>Equiv.</th></tr></thead>
                    <tbody>
                        <tr><td>1 ha</td><td>10 000 m²</td></tr>
                        <tr><td>1 are</td><td>100 m²</td></tr>
                        <tr><td>1 centiare</td><td>1 m²</td></tr>
                        <tr><td style="padding-top:12px;font-weight:600;color:var(--text-muted);" colspan="2">Longueur</td></tr>
                        <tr><td>1 km</td><td>1 000 m</td></tr>
                        <tr><td>1 pied</td><td>0.3048 m</td></tr>
                        <tr><td>1 pouce</td><td>0.0254 m</td></tr>
                        <tr><td style="padding-top:12px;font-weight:600;color:var(--text-muted);" colspan="2">Angle</td></tr>
                        <tr><td>360°</td><td>400 g</td></tr>
                        <tr><td>180°</td><td>π rad</td></tr>
                        <tr><td>100 g</td><td>90°</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ── NAVIGATION TABS ──
function showTool(name, btn) {
    document.querySelectorAll('.tool-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tool-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('panel-' + name).classList.add('active');
}

// ═══════════════════════════════
// 1. DISTANCE
// ═══════════════════════════════
async function calculerDistance() {
    const x1 = parseFloat(document.getElementById('d_x1').value);
    const y1 = parseFloat(document.getElementById('d_y1').value);
    const x2 = parseFloat(document.getElementById('d_x2').value);
    const y2 = parseFloat(document.getElementById('d_y2').value);
    if ([x1,y1,x2,y2].some(isNaN)) return alert('Remplissez tous les champs.');

    const res = await fetch('{{ route("outils.distance") }}', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body:JSON.stringify({x1,y1,x2,y2})
    });
    const d = await res.json();
    document.getElementById('r-dm').textContent  = d.distance_m  + ' m';
    document.getElementById('r-dkm').textContent = d.distance_km + ' km';
    document.getElementById('res-distance').style.display = 'block';
}

// ═══════════════════════════════
// 2. SURFACE (GAUSS / SHOELACE)
// ═══════════════════════════════
let ptCount = 0;
function ajouterPointSurface() {
    ptCount++;
    const list = document.getElementById('surface-points');
    const row  = document.createElement('div');
    row.id = 'pt-' + ptCount;
    row.className = 'point-row';
    row.innerHTML = `
        <div class="point-num">P${ptCount}</div>
        <input type="number" placeholder="X (m)" step="any" class="form-control-topo pt-x" style="font-size:13px;">
        <input type="number" placeholder="Y (m)" step="any" class="form-control-topo pt-y" style="font-size:13px;">
        <button class="del-btn" onclick="this.closest('[id]').remove()"><i class="bi bi-x-lg"></i></button>
    `;
    list.appendChild(row);
}
async function calculerSurface() {
    const points = [];
    document.querySelectorAll('#surface-points .point-row').forEach(r => {
        const x = parseFloat(r.querySelector('.pt-x').value);
        const y = parseFloat(r.querySelector('.pt-y').value);
        if(!isNaN(x) && !isNaN(y)) points.push([x, y]);
    });
    if(points.length < 3) return alert('Minimum 3 points requis.');
    const res = await fetch('{{ route("outils.surface") }}', {
        method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body:JSON.stringify({points})
    });
    const d = await res.json();
    document.getElementById('r-sm2').textContent = d.surface_m2 + ' m²';
    document.getElementById('r-sar').textContent  = d.surface_ar + ' ares';
    document.getElementById('r-sha').textContent  = d.surface_ha + ' ha';
    document.getElementById('r-sp').textContent   = d.perimetre  + ' m';
    document.getElementById('res-surface').style.display = 'block';
}
// Init 3 points
window.addEventListener('load', () => {
    ajouterPointSurface(); ajouterPointSurface(); ajouterPointSurface();
    ajouterLot(); ajouterLot();
});

// ═══════════════════════════════
// 3. GISEMENT
// ═══════════════════════════════
function calculerGisement() {
    const x1 = parseFloat(document.getElementById('g_x1').value);
    const y1 = parseFloat(document.getElementById('g_y1').value);
    const x2 = parseFloat(document.getElementById('g_x2').value);
    const y2 = parseFloat(document.getElementById('g_y2').value);
    if([x1,y1,x2,y2].some(isNaN)) return alert('Remplissez tous les champs.');

    const dx = x2 - x1, dy = y2 - y1;
    let g = Math.atan2(dx, dy) * 180 / Math.PI;
    if(g < 0) g += 360;

    const dist = Math.sqrt(dx*dx + dy*dy);
    const grad = g * (400/360);

    // DMS
    const deg  = Math.floor(g);
    const minF = (g - deg) * 60;
    const min  = Math.floor(minF);
    const sec  = Math.round((minF - min) * 60);

    document.getElementById('r-gdeg').textContent  = g.toFixed(4) + '°';
    document.getElementById('r-gdms').textContent  = `${deg}° ${min}' ${sec}''`;
    document.getElementById('r-ggrad').textContent = grad.toFixed(4) + ' g';
    document.getElementById('r-gdist').textContent = dist.toFixed(4) + ' m';
    document.getElementById('res-gisement').style.display = 'block';
}

// ═══════════════════════════════
// 4. EMPRISE AU SOL
// ═══════════════════════════════
function calculerEmprise() {
    const parcelle = parseFloat(document.getElementById('e_parcelle').value);
    const batiment = parseFloat(document.getElementById('e_batiment').value);
    const etages   = parseInt(document.getElementById('e_etages').value)   || 1;
    const plancher = parseFloat(document.getElementById('e_plancher').value) || batiment;

    if(isNaN(parcelle)||isNaN(batiment)||parcelle<=0||batiment<=0)
        return alert('Entrez des surfaces valides.');
    if(batiment > parcelle)
        return alert('La surface du batiment ne peut pas depasser la surface de la parcelle.');

    const ces   = (batiment / parcelle) * 100;
    const libre = parcelle - batiment;
    const shon  = plancher * etages;

    document.getElementById('r-ces').textContent   = ces.toFixed(2)   + '%';
    document.getElementById('r-libre').textContent = libre.toFixed(2)  + ' m²';
    document.getElementById('r-shon').textContent  = shon.toFixed(2)   + ' m²';
    document.getElementById('res-emprise').style.display = 'block';
}

// ═══════════════════════════════
// 5. TANTIEMES
// ═══════════════════════════════
let lotCount = 0;
function ajouterLot() {
    lotCount++;
    const list = document.getElementById('lots-list');
    const row  = document.createElement('div');
    row.id = 'lot-' + lotCount;
    row.className = 'point-row';
    row.innerHTML = `
        <div class="point-num">L${lotCount}</div>
        <input type="text"   placeholder="Nom du lot (ex: Appt. A1)" class="form-control-topo lot-nom"     style="font-size:13px;">
        <input type="number" placeholder="Surface m²"                 class="form-control-topo lot-surface" style="font-size:13px;" step="any" min="0.01">
        <button class="del-btn" onclick="document.getElementById('lot-${lotCount}').remove()"><i class="bi bi-x-lg"></i></button>
    `;
    list.appendChild(row);
}
async function calculerTantiemes() {
    const lots = [];
    document.querySelectorAll('#lots-list .point-row').forEach(r => {
        const nom     = r.querySelector('.lot-nom')?.value?.trim();
        const surface = parseFloat(r.querySelector('.lot-surface')?.value);
        if(nom && !isNaN(surface) && surface > 0) lots.push({nom, surface});
    });
    if(lots.length === 0) return alert('Ajoutez au moins un lot valide.');
    const base = parseInt(document.getElementById('t_base').value);
    const res = await fetch('{{ route("outils.tantiemes") }}', {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body:JSON.stringify({lots, base})
    });
    const d = await res.json();
    if(d.error) return alert(d.error);
    const tbody = document.getElementById('tantiemes-tbody');
    tbody.innerHTML = '';
    d.lots.forEach(lot => {
        tbody.innerHTML += `<tr style="border-bottom:1px solid var(--border);">
            <td style="padding:9px 8px;color:var(--white);font-weight:500;">${lot.nom}</td>
            <td style="padding:9px 8px;text-align:right;color:rgba(255,255,255,0.7);">${lot.surface.toLocaleString('fr-FR')} m²</td>
            <td style="padding:9px 8px;text-align:right;color:rgba(255,255,255,0.7);">${lot.pct.toFixed(2)}%</td>
            <td style="padding:9px 8px;text-align:right;color:#60a5fa;font-weight:700;">${lot.tantiemes.toLocaleString('fr-FR')}</td>
        </tr>`;
    });
    document.getElementById('t-total-surf').textContent = d.surface_totale.toLocaleString('fr-FR') + ' m²';
    document.getElementById('t-total').textContent = d.total_tantiemes.toLocaleString('fr-FR');
    document.getElementById('t-info').textContent = `${d.lots.length} lots | base ${d.base}`;
    document.getElementById('res-tantiemes').style.display = 'block';
}

// ═══════════════════════════════
// 6. CONVERSION D'UNITES
// ═══════════════════════════════
const convMap = {
    m2:   { label:'m²',       compat:['ha','ar','ca'] },
    ha:   { label:'ha',       compat:['m2','ar','ca'] },
    ar:   { label:'are',      compat:['m2','ha','ca'] },
    ca:   { label:'ca',       compat:['m2','ha','ar'] },
    m:    { label:'m',        compat:['km','cm','mm','pied','pouce'] },
    km:   { label:'km',       compat:['m','cm','mm','pied'] },
    cm:   { label:'cm',       compat:['m','mm','km'] },
    mm:   { label:'mm',       compat:['m','cm'] },
    pied: { label:'pied',     compat:['m','km','cm'] },
    pouce:{ label:'pouce',    compat:['m','cm','mm'] },
    deg:  { label:'degre',    compat:['rad','grad'] },
    rad:  { label:'radian',   compat:['deg','grad'] },
    grad: { label:'grade(g)', compat:['deg','rad'] },
};
function updateToOptions() {
    const de  = document.getElementById('c_de').value;
    const sel = document.getElementById('c_vers');
    sel.innerHTML = '';
    (convMap[de]?.compat || []).forEach(v => {
        const o = document.createElement('option');
        o.value = v; o.textContent = convMap[v]?.label || v;
        sel.appendChild(o);
    });
}
updateToOptions();
async function convertirUnites() {
    const valeur = document.getElementById('c_valeur').value;
    const de     = document.getElementById('c_de').value;
    const vers   = document.getElementById('c_vers').value;
    if(!valeur) return alert('Entrez une valeur.');
    const res = await fetch('{{ route("outils.convertir") }}', {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body:JSON.stringify({valeur, de, vers})
    });
    const d = await res.json();
    if(d.error) return alert(d.error);
    document.getElementById('r-conv-from').textContent = `${d.valeur_initiale} ${d.unite_de}`;
    document.getElementById('r-conv-val').textContent  = d.resultat;
    document.getElementById('r-conv-to').textContent   = `→ ${d.unite_vers.toUpperCase()}`;
    document.getElementById('res-conversion').style.display = 'block';
}
</script>
@endpush
