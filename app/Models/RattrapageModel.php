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
        'id_ds',
        'code',
        'date_rattrapage',
        'duree_minutes',
        'heure_debut',
        'etat',
        'mail_envoye',
        'date_creation',
        'salle',
        'type_exam'
    ];

    protected $useTimestamps = false;

    /**
     * Liste des rattrapages avec les infos utiles (étudiant, enseignant, matière, semestre, état)
     */
    public function getAllWithDetails()
    {
        return $this->select('
                rattrapage.*, 
                etudiant.nom AS nom_etudiant, 
                etudiant.prenom AS prenom_etudiant,
                enseignant.nom AS nom_enseignant,
                enseignant.prenom AS prenom_enseignant,
                matiere.nom_matiere,
                semestre.nom_semestre
            ')
            ->join('etudiant', 'rattrapage.code = etudiant.code')
            ->join('ds', 'rattrapage.id_ds = ds.id_ds')
            ->join('matiere', 'ds.id_matiere = matiere.id_matiere')
            ->join('semestre', 'matiere.id_semestre = semestre.id_semestre')
            ->join('enseignant', 'ds.code_enseignant = enseignant.code')
            ->orderBy('rattrapage.date_rattrapage', 'DESC')
            ->orderBy('etudiant.nom', 'ASC')
            ->findAll();          
    }

    public function insertRattrapage(array $data)
    {
        return $this->insert($data);
    }
}
