<?php

namespace App\Models;

use CodeIgniter\Model;

class RattrapageModel extends Model
{
    protected $table      = 'rattrapage';
    protected $primaryKey = 'id_rattrapage';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_etudiant',
        'id_ds',
        'id_matiere',
        'id_semestre',
        'id_enseignant',
        'date_rattrapage',
        'duree_minutes',
        'absent',
        'absent_justifie',
        'id_etat',
        'mail_envoye',
        'date_creation',
    ];

    protected $useTimestamps = false;

    /**
     * Liste des rattrapages avec les infos utiles (étudiant, enseignant, matière, semestre, état)
     */
    public function getAllWithDetails()
    {
        return $this->select(
                'rattrapage.*,
                 etu.numero_etudiant,
                 pe_etud.nom  AS nom_etud,
                 pe_etud.prenom AS prenom_etud,
                 mat.nom AS matiere,
                 sem.code AS semestre,
                 pe_ens.nom  AS nom_ens,
                 pe_ens.prenom AS prenom_ens,
                 etat.code AS code_etat,
                 etat.libelle AS libelle_etat'
            )
            ->join('etudiant etu', 'etu.id_etudiant = rattrapage.id_etudiant')
            ->join('personne pe_etud', 'pe_etud.id_personne = etu.id_personne')
            ->join('enseignant ens', 'ens.id_enseignant = rattrapage.id_enseignant')
            ->join('personne pe_ens', 'pe_ens.id_personne = ens.id_personne')
            ->join('semestre sem', 'sem.id_semestre = rattrapage.id_semestre')
            ->join('etat_rattrapage etat', 'etat.id_etat = rattrapage.id_etat')
            ->join('matiere mat', 'mat.id_matiere = rattrapage.id_matiere', 'left')
            ->orderBy('date_rattrapage', 'ASC')
            ->findAll();
    }
}
