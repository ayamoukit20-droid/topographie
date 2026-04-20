<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OutilsTopoController extends Controller
{
    public function index()
    {
        return view('outils.index');
    }

    /**
     * Calcul distance entre 2 points (coordonnées planes X, Y)
     */
    public function calculerDistance(Request $request)
    {
        $request->validate([
            'x1' => 'required|numeric',
            'y1' => 'required|numeric',
            'x2' => 'required|numeric',
            'y2' => 'required|numeric',
        ]);

        $dx = $request->x2 - $request->x1;
        $dy = $request->y2 - $request->y1;
        $distance = sqrt($dx * $dx + $dy * $dy);

        return response()->json([
            'distance_m'  => round($distance, 4),
            'distance_km' => round($distance / 1000, 6),
        ]);
    }

    /**
     * Calcul surface d'un polygone (formule du lacet / Shoelace)
     * Points format: [[x1,y1],[x2,y2],...]
     */
    public function calculerSurface(Request $request)
    {
        $request->validate([
            'points'    => 'required|array|min:3',
            'points.*' => 'array|size:2',
        ]);

        $points = $request->points;
        $n = count($points);
        $aire = 0;

        for ($i = 0; $i < $n; $i++) {
            $j = ($i + 1) % $n;
            $aire += ($points[$i][0] * $points[$j][1]);
            $aire -= ($points[$j][0] * $points[$i][1]);
        }

        $surface = abs($aire) / 2;

        return response()->json([
            'surface_m2' => round($surface, 4),
            'surface_ha' => round($surface / 10000, 6),
            'surface_ar' => round($surface / 100, 4),
            'perimetre'  => $this->calculerPerimetre($points),
        ]);
    }

    /**
     * Conversion d'unités
     */
    public function convertirUnites(Request $request)
    {
        $request->validate([
            'valeur' => 'required|numeric',
            'de'     => 'required|string',
            'vers'   => 'required|string',
        ]);

        $valeur = (float) $request->valeur;
        $de     = $request->de;
        $vers   = $request->vers;

        $conversions = [
            // Surface
            'm2_ha'    => 1 / 10000,  'ha_m2'    => 10000,
            'm2_ar'    => 1 / 100,    'ar_m2'    => 100,
            'm2_ca'    => 1,          'ca_m2'    => 1,
            'ha_ar'    => 100,        'ar_ha'    => 1 / 100,
            'ha_ca'    => 10000,      'ca_ha'    => 1 / 10000,
            'ar_ca'    => 100,        'ca_ar'    => 1 / 100,
            // Longueur
            'm_km'     => 1 / 1000,   'km_m'     => 1000,
            'm_cm'     => 100,        'cm_m'     => 1 / 100,
            'm_mm'     => 1000,       'mm_m'     => 1 / 1000,
            'cm_mm'    => 10,         'mm_cm'    => 1 / 10,
            'km_cm'    => 100000,     'cm_km'    => 1 / 100000,
            'km_mm'    => 1000000,    'mm_km'    => 1 / 1000000,
            'm_pied'   => 3.28084,    'pied_m'   => 1 / 3.28084,
            'm_pouce'  => 39.3701,    'pouce_m'  => 1 / 39.3701,
            'km_pied'  => 3280.84,    'pied_km'  => 1 / 3280.84,
            'cm_pied'  => 1/30.48,    'pied_cm'  => 30.48,
            'cm_pouce' => 1/2.54,     'pouce_cm' => 2.54,
            'mm_pouce' => 1/25.4,     'pouce_mm' => 25.4,
            // Angle
            'deg_rad'  => M_PI / 180, 'rad_deg'  => 180 / M_PI,
            'deg_grad' => 10 / 9,     'grad_deg' => 0.9,
            'rad_grad' => 200/M_PI,   'grad_rad' => M_PI / 200,
        ];

        $key = $de . '_' . $vers;

        if (!isset($conversions[$key])) {
            return response()->json(['error' => 'Conversion non supportée'], 422);
        }

        $resultat = $valeur * $conversions[$key];

        return response()->json([
            'valeur_initiale' => $valeur,
            'unite_de'        => $de,
            'unite_vers'      => $vers,
            'resultat'        => round($resultat, 8),
        ]);
    }

    private function calculerPerimetre(array $points): float
    {
        $n = count($points);
        $perimetre = 0;
        for ($i = 0; $i < $n; $i++) {
            $j = ($i + 1) % $n;
            $dx = $points[$j][0] - $points[$i][0];
            $dy = $points[$j][1] - $points[$i][1];
            $perimetre += sqrt($dx * $dx + $dy * $dy);
        }
        return round($perimetre, 4);
    }

    /**
     * Calcul des tantièmes pour la copropriété
     * Chaque lot reçoit un nombre de millièmes proportionnel à sa surface
     */
    public function calculerTantiemes(Request $request)
    {
        $request->validate([
            'lots'           => 'required|array|min:1',
            'lots.*.nom'     => 'required|string',
            'lots.*.surface' => 'required|numeric|min:0.01',
            'base'           => 'nullable|integer|in:100,1000,10000',
        ]);

        $lots  = $request->lots;
        $base  = $request->base ?? 1000; // millièmes par défaut
        $total = array_sum(array_column($lots, 'surface'));

        if ($total <= 0) {
            return response()->json(['error' => 'La surface totale doit être superieure à 0.'], 422);
        }

        $result = [];
        $totalTantiemes = 0;

        foreach ($lots as $lot) {
            $tantiemes = round(($lot['surface'] / $total) * $base);
            $totalTantiemes += $tantiemes;
            $result[] = [
                'nom'        => $lot['nom'],
                'surface'    => round($lot['surface'], 4),
                'pct'        => round(($lot['surface'] / $total) * 100, 3),
                'tantiemes'  => $tantiemes,
            ];
        }

        // Correction d'arrondi : ajuster le dernier lot pour atteindre exactement $base
        if (count($result) > 0 && $totalTantiemes !== $base) {
            $result[count($result) - 1]['tantiemes'] += ($base - $totalTantiemes);
        }

        return response()->json([
            'lots'           => $result,
            'surface_totale' => round($total, 4),
            'base'           => $base,
            'total_tantiemes'=> $base,
        ]);
    }
}

