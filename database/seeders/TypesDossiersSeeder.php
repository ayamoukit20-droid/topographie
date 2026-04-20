<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeDossier;
use App\Models\DocumentRequis;

class TypesDossiersSeeder extends Seeder
{
    public function run(): void
    {
        // Données ANCFCC officielles
        $types = [
            [
                'code'        => 'immatriculation',
                'nom'         => 'Immatriculation Fonciere',
                'description' => 'Premiere immatriculation d\'un bien immobilier au registre foncier',
                'couleur'     => 'green',
                'ordre'       => 1,
                'documents'   => [
                    ['nom' => 'Requisition d\'immatriculation',              'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 1],
                    ['nom' => 'PV de bornage',                               'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 2],
                    ['nom' => 'Plan de bornage',                             'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 3],
                    ['nom' => 'Plan de situation',                           'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 4],
                    ['nom' => 'Tableau de coordonnees (X, Y)',               'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 5],
                    ['nom' => 'Calcul de contenance',                        'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 6],
                    ['nom' => 'Recepisse de depot',                          'categorie' => 'complementaire', 'obligatoire' => false, 'ordre' => 7],
                ],
            ],
            [
                'code'        => 'maj',
                'nom'         => 'Mise a Jour (MAJ)',
                'description' => 'Mise a jour du titre foncier suite a une construction ou modification',
                'couleur'     => 'yellow',
                'ordre'       => 2,
                'documents'   => [
                    ['nom' => 'Titre foncier',                               'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 1],
                    ['nom' => 'Autorisation de construire',                  'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 2],
                    ['nom' => 'Plan architecte vise',                        'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 3],
                    ['nom' => 'Permis d\'habiter',                           'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 4],
                    ['nom' => 'Plan de situation',                           'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 5],
                    ['nom' => 'Plan de mise a jour',                         'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 6],
                    ['nom' => 'Calcul surface batie',                        'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 7],
                    ['nom' => 'Attestation d\'achevement des travaux',       'categorie' => 'complementaire', 'obligatoire' => false, 'ordre' => 8],
                ],
            ],
            [
                'code'        => 'copropriete',
                'nom'         => 'Copropriete',
                'description' => 'Division d\'un immeuble en lots independants avec parties communes',
                'couleur'     => 'blue',
                'ordre'       => 3,
                'documents'   => [
                    ['nom' => 'Titre foncier',                               'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 1],
                    ['nom' => 'Certificat de propriete',                     'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 2],
                    ['nom' => 'Autorisation de construire',                  'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 3],
                    ['nom' => 'Certificat de conformite',                    'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 4],
                    ['nom' => 'Note de renseignements',                      'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 5],
                    ['nom' => 'Plan de situation',                           'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 6],
                    ['nom' => 'Plan de masse',                               'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 7],
                    ['nom' => 'Plans architecturaux (RDC + tous etages)',    'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 8],
                    ['nom' => 'Tableau des surfaces (Tableau A)',            'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 9],
                    ['nom' => 'Tableau des tantiemes (Tableau B)',           'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 10],
                    ['nom' => 'Reglement de copropriete',                   'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 11],
                    ['nom' => 'Etat descriptif de division',                'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 12],
                ],
            ],
            [
                'code'        => 'morcellement',
                'nom'         => 'Morcellement',
                'description' => 'Division d\'une parcelle en plusieurs lots distincts',
                'couleur'     => 'red',
                'ordre'       => 4,
                'documents'   => [
                    ['nom' => 'Note de renseignements',                      'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 1],
                    ['nom' => 'Autorisation de division (Wilaya)',           'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 2],
                    ['nom' => 'Plan de division (avant/apres)',              'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 3],
                    ['nom' => 'Calcul des nouvelles surfaces',               'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 4],
                    ['nom' => 'Plan cadastral',                              'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 5],
                    ['nom' => 'PV de bornage des nouveaux lots',            'categorie' => 'complementaire', 'obligatoire' => false, 'ordre' => 6],
                ],
            ],
            [
                'code'        => 'lotissement',
                'nom'         => 'Lotissement',
                'description' => 'Creation d\'un ensemble de lots a batir avec voirie et reseaux',
                'couleur'     => 'purple',
                'ordre'       => 5,
                'documents'   => [
                    ['nom' => 'Plan de lotissement general',                 'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 1],
                    ['nom' => 'Cahier des charges',                          'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 2],
                    ['nom' => 'Plan de masse',                               'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 3],
                    ['nom' => 'Plan de voirie et reseaux (VRD)',            'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 4],
                    ['nom' => 'Plan des reseaux (eau, electricite, assain.)','categorie' => 'principal',      'obligatoire' => true,  'ordre' => 5],
                    ['nom' => 'PV de reception des travaux',                 'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 6],
                    ['nom' => 'Permis de lotir',                             'categorie' => 'principal',      'obligatoire' => true,  'ordre' => 7],
                    ['nom' => 'Tableau recapitulatif des lots',              'categorie' => 'complementaire', 'obligatoire' => false, 'ordre' => 8],
                ],
            ],
        ];

        foreach ($types as $typeData) {
            $documents = $typeData['documents'];
            unset($typeData['documents']);

            // Upsert pour eviter les doublons si on re-seede
            $type = TypeDossier::updateOrCreate(
                ['code' => $typeData['code']],
                $typeData
            );

            // Supprimer les anciens documents requis puis reinserter
            $type->documentsRequis()->delete();
            foreach ($documents as $doc) {
                $type->documentsRequis()->create($doc);
            }
        }

        // Mettre à jour les dossiers existants avec la FK
        \DB::statement("
            UPDATE dossiers d
            JOIN types_dossiers t ON t.code = d.type_dossier
            SET d.type_dossier_id = t.id
            WHERE d.type_dossier_id IS NULL
        ");

        $this->command->info('5 types ANCFCC + documents requis inserés avec succes.');
    }
}
