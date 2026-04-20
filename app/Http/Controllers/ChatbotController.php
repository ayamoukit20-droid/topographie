<?php

namespace App\Http\Controllers;

use App\Models\ChatbotQa;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot.index');
    }

    public function repondre(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $message  = $request->message;
        $msgLower = strtolower(trim($message));

        // ────────────────────────────────────────
        // 1. SALUTATIONS
        // ────────────────────────────────────────
        $salutations = ['bonjour', 'salut', 'hello', 'bonsoir', 'hi', 'salam', 'مرحبا', 'السلام'];
        foreach ($salutations as $s) {
            if (str_contains($msgLower, $s)) {
                return response()->json([
                    'reponse' => "Bonjour ! 👋 Je suis **TopoBot**, votre assistant topographique intelligent.\n\nJe peux vous aider sur :\n• 🗂️ Bornage & Lotissement\n• 📄 Documents requis\n• 📐 Calculs topographiques (distance, surface, conversion)\n• 🏛️ Procédures cadastrales\n\nQuelle est votre question ?",
                    'found'   => true,
                ]);
            }
        }

        // ────────────────────────────────────────
        // 2. RÈGLES PRIORITAIRES (patterns directs)
        // ────────────────────────────────────────

        // Remerciements
        if (preg_match('/\b(merci|thank|شكر)\b/i', $msgLower)) {
            return response()->json([
                'reponse' => "De rien ! 😊 Je suis là pour vous aider. N'hésitez pas à poser d'autres questions.",
                'found'   => true,
            ]);
        }

        // Au revoir
        if (preg_match('/\b(au revoir|bye|goodbye|bonne journée|bonne soirée)\b/i', $msgLower)) {
            return response()->json([
                'reponse' => "Au revoir ! 👋 Bonne continuation dans vos travaux topographiques. N'hésitez pas à revenir !",
                'found'   => true,
            ]);
        }

        // Aide générale
        if (preg_match('/\b(aide|help|comment utiliser|que peux-tu|que fais-tu|quoi faire)\b/i', $msgLower)) {
            return response()->json([
                'reponse' => "Je suis **TopoBot** et je peux vous aider sur :\n\n📌 **Opérations topographiques** : bornage, lotissement, morcellement, fusion, implantation\n📄 **Documents** : PV de bornage, plan de masse, titres, cadastre\n📐 **Calculs** : distance entre points, surface, conversion d'unités\n🗂️ **Gestion des dossiers** : créer, modifier, statuts\n\nPosez votre question librement !",
                'found'   => true,
            ]);
        }

        // Calcul de surface spécifique
        if (preg_match('/\b(calcul|calculer|formule|shoelace|lacet)\b.*\b(surface|aire|superfici)\b|\b(surface|aire)\b.*\b(calcul|calculer|comment|m2|m²)\b/i', $msgLower)) {
            return response()->json([
                'reponse' => "**Calcul de surface d'une parcelle** 📐\n\nOn utilise la **formule du lacet** (Shoelace) :\n\n```\nS = |Σ(xi × yi+1 − xi+1 × yi)| / 2\n```\n\n**Exemple avec 4 points :**\n- P1(0, 0), P2(50, 0), P3(50, 30), P4(0, 30)\n- S = (50×0 − 0×0) + (50×30 − 50×0) + (0×30 − 50×30) + (0×0 − 0×30)\n- S = 1500 m²\n\n💡 Utilisez l'**outil Calcul Surface** de la plateforme pour le calcul automatique !",
                'found'   => true,
            ]);
        }

        // Calcul de distance
        if (preg_match('/\b(calcul|calculer|formule)\b.*\b(distance|longueur)\b|\b(distance)\b.*\b(calcul|points|coordonnées)\b/i', $msgLower)) {
            return response()->json([
                'reponse' => "**Calcul de distance entre deux points** 📏\n\nOn utilise la **formule de Pythagore** :\n\n```\nD = √((x2 - x1)² + (y2 - y1)²)\n```\n\n**Exemple :**\n- P1(100, 200) et P2(170, 250)\n- D = √((170−100)² + (250−200)²)\n- D = √(4900 + 2500) = √7400 ≈ **86.02 m**\n\n💡 Utilisez l'**outil Calcul Distance** de la plateforme pour un résultat instantané !",
                'found'   => true,
            ]);
        }

        // Conversion d'unités
        if (preg_match('/\b(convertir|conversion|m2|m²|hectare|ha|are|pied)\b.*\b(m2|m²|hectare|ha|are|pied|unité)\b|\b(convertir|conversion)\b/i', $msgLower)) {
            return response()->json([
                'reponse' => "**Conversion d'unités de surface** 🔄\n\n| Unité | Équivalence |\n|-------|-------------|\n| 1 hectare (ha) | = 10 000 m² |\n| 1 are | = 100 m² |\n| 1 km² | = 1 000 000 m² |\n| 1 ha | = 100 ares |\n| 1 m² | = 0,0001 ha |\n\n**Exemples :**\n- 2,5 ha = **25 000 m²**\n- 3 500 m² = **0,35 ha**\n\n💡 Utilisez l'**outil Conversion** de la plateforme pour toutes les conversions !",
                'found'   => true,
            ]);
        }

        // Bornage
        if (preg_match('/\b(bornage|born|borne)\b/i', $msgLower)) {
            return response()->json([
                'reponse' => "**Le Bornage** 🏚️\n\nLe bornage est une opération topographique qui consiste à **déterminer et matérialiser** sur le terrain les limites d'une propriété foncière.\n\n**Étapes principales :**\n1. Convocation des propriétaires riverains\n2. Relevé des coordonnées sur le terrain\n3. Pose des bornes physiques\n4. Rédaction du **Procès-Verbal (PV) de bornage**\n5. Signature de toutes les parties\n\n**Documents requis :**\n• Titre de propriété original\n• Plan cadastral de la parcelle\n• PV de bornage signé\n• Fiche de coordonnées des bornes",
                'found'   => true,
            ]);
        }

        // Lotissement
        if (preg_match('/\b(lotissement|lotir|lot)\b/i', $msgLower)) {
            return response()->json([
                'reponse' => "**Le Lotissement** 🏘️\n\nUn lotissement est une opération d'aménagement foncier qui divise une propriété en **plusieurs lots constructibles** avec création d'une voie d'accès commune.\n\n**Documents requis :**\n• Permis de lotir\n• Plan de lotissement visé\n• Règlement de lotissement\n• Cahier des charges\n• Plan de la voirie\n\n**Différence avec le morcellement :**\nLe lotissement crée une voie commune, le morcellement non — chaque lot doit avoir une façade sur une voie existante.",
                'found'   => true,
            ]);
        }

        // Morcellement
        if (preg_match('/\b(morcellement|morceler)\b/i', $msgLower)) {
            return response()->json([
                'reponse' => "**Le Morcellement** ✂️\n\nLe morcellement consiste à **diviser une grande propriété** en plusieurs parcelles plus petites **sans création de voie commune** (contrairement au lotissement).\n\n**Conditions :**\n• Chaque nouvelle parcelle doit avoir une **façade sur une voie existante**\n• Superficie minimale selon le règlement local\n• Dossier technique à déposer au registre foncier\n\n**Documents :** Titre de propriété, plan parcellaire, demande de morcellement.",
                'found'   => true,
            ]);
        }

        // Fusion
        if (preg_match('/\b(fusion|fusionner|réunion|réunir|regroupement)\b/i', $msgLower)) {
            return response()->json([
                'reponse' => "**La Fusion de parcelles** 🔗\n\nLa fusion est l'opération **inverse du morcellement** : elle réunit plusieurs parcelles contiguës appartenant au même propriétaire en une seule parcelle.\n\n**Conditions :**\n• Parcelles **contiguës** (qui se touchent)\n• Même propriétaire pour toutes les parcelles\n• Mise à jour au registre foncier obligatoire\n\n**Documents :** Titres des parcelles concernées, demande de fusion, nouveau plan parcellaire.",
                'found'   => true,
            ]);
        }

        // PV / Procès-verbal
        if (preg_match('/\bpv\b|procès.verbal|proces.verbal/i', $msgLower)) {
            return response()->json([
                'reponse' => "**Le Procès-Verbal (PV) de bornage** 📄\n\nC'est le **document juridique officiel** qui consigne les résultats de l'opération de bornage.\n\n**Contenu du PV :**\n• Coordonnées des bornes posées\n• Surfaces calculées des parcelles\n• Description des limites\n• Informations des propriétaires riverains\n• **Signatures** de toutes les parties et du topographe\n\nSans signature de toutes les parties, le bornage est dit **contradictoire**.",
                'found'   => true,
            ]);
        }

        // Plan de masse
        if (preg_match('/\bplan\b.*\bmasse\b|\bplan de masse\b/i', $msgLower)) {
            return response()->json([
                'reponse' => "**Le Plan de masse** 🗺️\n\nDocument graphique représentant en **vue de dessus** l'ensemble d'une propriété ou d'un projet de construction.\n\n**Contenu :**\n• Limites et dimensions de la propriété\n• Orientation (Nord)\n• Constructions existantes ou projetées\n• Distances aux limites\n• Échelle (généralement 1:200 ou 1:500)\n\nIl est obligatoire pour les demandes de **permis de construire** et de **permis de lotir**.",
                'found'   => true,
            ]);
        }

        // Cadastre
        if (preg_match('/\bcadastre\b|cadastral/i', $msgLower)) {
            return response()->json([
                'reponse' => "**Le Cadastre** 🏛️\n\nLe cadastre est le **registre public** qui recense et identifie toutes les propriétés foncières d'un territoire.\n\n**Informations cadastrales :**\n• Numéro de parcelle unique\n• Limites et superficie\n• Nom du propriétaire\n• Nature du terrain (urbain, agricole, forestier)\n\n**Utilité :** Base de référence pour tous les dossiers topographiques, le cadastre sert aussi pour le calcul de la **taxe foncière**.",
                'found'   => true,
            ]);
        }

        // Implantation
        if (preg_match('/\bimplantation\b|implanter/i', $msgLower)) {
            return response()->json([
                'reponse' => "**L'Implantation topographique** 📍\n\nL'implantation consiste à **reporter sur le terrain** des points définis sur un plan de projet.\n\n**C'est l'opération inverse du levé topographique :**\n• Levé : on mesure le terrain → on fait un plan\n• Implantation : on a un plan → on matérialise sur le terrain\n\n**Applications :**\n• Axes des bâtiments\n• Réseaux (eau, assainissement, électricité)\n• Voiries et infrastructures\n• Bornes de propriété",
                'found'   => true,
            ]);
        }

        // Statuts de dossier
        if (preg_match('/\bstatut\b|état.*dossier|dossier.*état|en cours|terminé|archivé/i', $msgLower)) {
            return response()->json([
                'reponse' => "**Statuts des dossiers sur TopoAssist** 🗂️\n\n| Statut | Signification |\n|--------|---------------|\n| 🟡 **En cours** | Travaux démarrés, en cours d'exécution |\n| 🟢 **Terminé** | Opération finalisée et documentée |\n| 🔵 **En attente** | En attente d'un document ou d'une signature |\n| 🔴 **Archivé** | Dossier clôturé et archivé |\n\nVous pouvez modifier le statut depuis la **fiche de chaque dossier**.",
                'found'   => true,
            ]);
        }

        // Créer un dossier
        if (preg_match('/créer.*dossier|nouveau.*dossier|comment.*dossier/i', $msgLower)) {
            return response()->json([
                'reponse' => "**Comment créer un nouveau dossier ?** 🗂️\n\n1. Cliquez sur **\"Nouveau Dossier\"** dans le menu latéral\n2. Sélectionnez le **type d'opération** (bornage, lotissement, etc.)\n3. Renseignez les informations :\n   - Nom du propriétaire\n   - Localisation de la parcelle\n   - Description de l'opération\n   - Date de début\n4. Cliquez sur **\"Enregistrer\"**\n5. Ajoutez ensuite vos **documents** depuis la fiche du dossier\n\n💡 Vous pouvez modifier un dossier à tout moment.",
                'found'   => true,
            ]);
        }

        // Documents / formats de fichiers
        if (preg_match('/\b(document|fichier|format|upload|télécharger|joindre|pdf|word|excel)\b/i', $msgLower)) {
            return response()->json([
                'reponse' => "**Gestion des documents** 📁\n\n**Formats acceptés :**\n• 📄 PDF, DOC, DOCX (documents)\n• 📊 XLS, XLSX (tableurs)\n• 🖼️ JPG, JPEG, PNG (images)\n• 📦 ZIP, RAR (archives)\n\n**Taille maximale :** 10 MB par fichier\n\n**Comment ajouter un document :**\n1. Ouvrez votre dossier\n2. Faites défiler jusqu'à la section **\"Documents\"**\n3. Cliquez sur **\"Ajouter un document\"**\n4. Sélectionnez votre fichier et confirmez",
                'found'   => true,
            ]);
        }

        // ────────────────────────────────────────
        // 3. RECHERCHE EN BASE DE DONNÉES (fallback)
        // ────────────────────────────────────────
        $qa = ChatbotQa::rechercherParMotsCles($message);

        if ($qa) {
            return response()->json([
                'reponse'  => $qa->reponse,
                'question' => $qa->question,
                'found'    => true,
            ]);
        }

        // ────────────────────────────────────────
        // 4. RÉPONSE PAR DÉFAUT
        // ────────────────────────────────────────
        return response()->json([
            'reponse' => "❓ Je n'ai pas trouvé de réponse précise à votre question.\n\nEssayez des mots-clés comme :\n**bornage**, **lotissement**, **morcellement**, **fusion**, **PV**, **cadastre**, **implantation**, **calcul distance**, **calcul surface**, **conversion hectares**\n\nOu reformulez votre question avec plus de détails.",
            'found'   => false,
        ]);
    }
}
