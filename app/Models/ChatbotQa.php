<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotQa extends Model
{
    use HasFactory;

    protected $table = 'chatbot_qas';

    protected $fillable = [
        'question',
        'reponse',
        'mots_cles',
        'categorie',
    ];

    /**
     * Recherche par mots-clés dans la question ou les mots_cles
     */
    public static function rechercherParMotsCles(string $query): ?self
    {
        $query = strtolower(trim($query));
        $words = explode(' ', $query);

        // Chercher une correspondance dans les mots-clés
        $all = self::all();

        $best = null;
        $bestScore = 0;

        foreach ($all as $qa) {
            $score = 0;
            $motsCles = explode(',', strtolower($qa->mots_cles));
            $questionWords = explode(' ', strtolower($qa->question));

            foreach ($words as $word) {
                if (strlen($word) < 2) continue;
                foreach ($motsCles as $mc) {
                    if (str_contains(trim($mc), $word) || str_contains($word, trim($mc))) {
                        $score += 2;
                    }
                }
                foreach ($questionWords as $qw) {
                    if (str_contains($qw, $word) || str_contains($word, $qw)) {
                        $score += 1;
                    }
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $qa;
            }
        }

        return $bestScore > 0 ? $best : null;
    }
}
