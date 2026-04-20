<?php

namespace Database\Seeders;

use App\Models\ChatbotQa;
use Illuminate\Database\Seeder;

class ChatbotSeeder extends Seeder
{
    public function run(): void
    {
        $qas = [
            [
                'question'  => 'Qu\'est-ce que le bornage ?',
                'reponse'   => 'Le bornage est une opération topographique qui consiste à déterminer et matérialiser sur le terrain les limites d\'une propriété foncière. Il est réalisé par un topographe agréé et donne lieu à un procès-verbal (PV) de bornage.',
                'mots_cles' => 'bornage,borne,limite,propriété,foncier',
                'categorie' => 'operations',
            ],
            [
                'question'  => 'Qu\'est-ce qu\'un lotissement ?',
                'reponse'   => 'Un lotissement est une opération d\'aménagement foncier consistant à diviser une propriété foncière en plusieurs lots destinés à être construits. Il nécessite un permis de lotir et comprend un plan de lotissement, un règlement et un cahier des charges.',
                'mots_cles' => 'lotissement,lot,division,aménagement,permis',
                'categorie' => 'operations',
            ],
            [
                'question'  => 'Quels documents sont nécessaires pour un dossier de bornage ?',
                'reponse'   => 'Pour un dossier de bornage, vous avez besoin de : 1) Titre de propriété original, 2) Plan cadastral de la parcelle, 3) Procès-verbal de bornage signé par toutes les parties, 4) Fiche de coordonnées des bornes, 5) Plan de bornage visé par le topographe.',
                'mots_cles' => 'documents,dossier,bornage,titre,cadastral,pv',
                'categorie' => 'documents',
            ],
            [
                'question'  => 'Comment calculer la surface d\'une parcelle ?',
                'reponse'   => 'La surface d\'une parcelle se calcule à partir des coordonnées de ses sommets en utilisant la formule du lacet (Shoelace) : S = |Σ(xi×yi+1 - xi+1×yi)| / 2. L\'outil "Calcul Surface" de la plateforme permet ce calcul automatiquement en entrant les coordonnées des points.',
                'mots_cles' => 'calcul,surface,parcelle,aire,formule,lacet,shoelace',
                'categorie' => 'calculs',
            ],
            [
                'question'  => 'Qu\'est-ce qu\'un morcellement ?',
                'reponse'   => 'Le morcellement est une opération qui consiste à diviser une grande propriété en plusieurs parcelles plus petites sans création de voie d\'accès commune (contrairement au lotissement). Chaque nouvelle parcelle doit avoir une façade sur une voie existante.',
                'mots_cles' => 'morcellement,division,parcelle,propriété',
                'categorie' => 'operations',
            ],
            [
                'question'  => 'Qu\'est-ce qu\'une fusion de parcelles ?',
                'reponse'   => 'La fusion est l\'opération inverse du morcellement : elle consiste à réunir plusieurs parcelles contiguës appartenant au même propriétaire en une seule parcelle. Elle nécessite un dossier technique et une mise à jour au registre foncier.',
                'mots_cles' => 'fusion,réunion,parcelle,contiguë,regroupement',
                'categorie' => 'operations',
            ],
            [
                'question'  => 'Comment convertir des hectares en mètres carrés ?',
                'reponse'   => '1 hectare (ha) = 10 000 mètres carrés (m²). Exemple : 2,5 ha = 25 000 m². Vous pouvez utiliser l\'outil de conversion d\'unités de la plateforme pour effectuer cette conversion automatiquement.',
                'mots_cles' => 'conversion,hectare,ha,mètre carré,m2,unité',
                'categorie' => 'calculs',
            ],
            [
                'question'  => 'Qu\'est-ce qu\'un procès-verbal de bornage ?',
                'reponse'   => 'Le procès-verbal (PV) de bornage est le document officiel qui consigne les résultats de l\'opération de bornage. Il contient : les coordonnées des bornes posées, les surfaces calculées, les signatures des propriétaires riverains et du topographe. C\'est un document juridique.',
                'mots_cles' => 'PV,procès-verbal,bornage,document,officiel',
                'categorie' => 'documents',
            ],
            [
                'question'  => 'Qu\'est-ce qu\'un plan de masse ?',
                'reponse'   => 'Le plan de masse est un document graphique représentant en vue de dessus l\'ensemble d\'une propriété ou d\'un projet de construction avec ses limites, dimensions, orientation et les constructions existantes ou projetées. Il est à l\'échelle et orienté vers le Nord.',
                'mots_cles' => 'plan,masse,document,graphique,construction,échelle',
                'categorie' => 'documents',
            ],
            [
                'question'  => 'Comment calculer la distance entre deux points ?',
                'reponse'   => 'La distance entre deux points P1(x1,y1) et P2(x2,y2) se calcule avec la formule de Pythagore : D = √((x2-x1)² + (y2-y1)²). L\'outil "Calcul Distance" de la plateforme vous permet de faire ce calcul directement en entrant les coordonnées.',
                'mots_cles' => 'distance,calcul,points,coordonnées,formule,pythagore',
                'categorie' => 'calculs',
            ],
            [
                'question'  => 'Qu\'est-ce que le cadastre ?',
                'reponse'   => 'Le cadastre est le registre public qui recense et identifie toutes les propriétés foncières d\'un territoire. Il attribue à chaque parcelle un numéro unique et indique ses limites, sa superficie et son propriétaire. Il sert de base pour les dossiers topographiques.',
                'mots_cles' => 'cadastre,registre,parcelle,propriété,foncier,numéro',
                'categorie' => 'general',
            ],
            [
                'question'  => 'Qu\'est-ce que l\'implantation topographique ?',
                'reponse'   => 'L\'implantation topographique consiste à reporter sur le terrain les points définis sur un plan de projet (axes de bâtiments, réseaux, voiries). C\'est l\'opération inverse du levé topographique : on part du plan pour matérialiser des points sur le terrain.',
                'mots_cles' => 'implantation,terrain,plan,reporter,matérialiser,levé',
                'categorie' => 'operations',
            ],
            [
                'question'  => 'Quel est le statut d\'un dossier en cours ?',
                'reponse'   => 'Un dossier "En cours" signifie que les travaux topographiques ont débuté mais ne sont pas encore terminés. Le dossier est en cours de traitement, les mesures sont en cours d\'exécution ou les documents sont en cours de préparation.',
                'mots_cles' => 'statut,en cours,dossier,traitement',
                'categorie' => 'dossiers',
            ],
            [
                'question'  => 'Comment créer un nouveau dossier ?',
                'reponse'   => 'Pour créer un nouveau dossier : 1) Cliquez sur "Nouveau Dossier" dans le menu, 2) Sélectionnez le type d\'opération (bornage, lotissement, etc.), 3) Renseignez les informations du propriétaire, 4) Ajoutez une description et la localisation, 5) Définissez la date de début et cliquez sur "Enregistrer".',
                'mots_cles' => 'créer,nouveau,dossier,étapes,comment',
                'categorie' => 'dossiers',
            ],
            [
                'question'  => 'Quels formats de fichiers sont acceptés pour les documents ?',
                'reponse'   => 'La plateforme accepte les formats suivants : PDF, DOC, DOCX (documents Word), XLS, XLSX (tableurs Excel), JPG, JPEG, PNG (images), ZIP, RAR (archives). La taille maximale par fichier est de 10 MB.',
                'mots_cles' => 'format,fichier,upload,document,pdf,word,excel,image',
                'categorie' => 'documents',
            ],
        ];

        foreach ($qas as $qa) {
            ChatbotQa::create($qa);
        }
    }
}
