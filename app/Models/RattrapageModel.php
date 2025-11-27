<?php

namespace App\Models;

use CodeIgniter\Model;

class RattrapageModel extends Model
{
    protected $table = 'rattrapage';
    protected $primaryKey = 'id_rattrapage';

    protected $returnType = 'array';
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
     * Récupère la liste des rattrapages avec pagination et filtres
     */
    public function getPaginatedRattrapages(int $perPage, array $filters = [])
    {
        $this->select('
            rattrapage.id_rattrapage,
            rattrapage.date_rattrapage,
            rattrapage.duree_minutes,
            rattrapage.heure_debut,
            rattrapage.salle,
            rattrapage.etat,
            ds.type_exam,
            ressource.coderessource,
            ressource.nomressource,
            enseignant.nom as enseignant_nom,
            enseignant.prenom as enseignant_prenom,
            semestre.code as semestre_code
        ');
        
        $this->join('ds', 'ds.id_ds = rattrapage.id_ds');
        $this->join('ressource', 'ressource.coderessource = ds.coderessource');
        $this->join('enseignant', 'enseignant.code = rattrapage.code');
        $this->join('semestre', 'semestre.id_semestre = ds.id_semestre');

        if (!empty($filters['keyword'])) {
            $this->groupStart()
                 ->like('ressource.nomressource', $filters['keyword'])
                 ->orLike('enseignant.nom', $filters['keyword'])
                 ->orLike('ressource.coderessource', $filters['keyword'])
                 ->groupEnd();
        }

        if (!empty($filters['resource'])) {
            $this->where('ds.coderessource', $filters['resource']);
        }

        if (!empty($filters['semester'])) {
            $this->where('semestre.code', $filters['semester']);
        }

        if (!empty($filters['teacher'])) {
            $this->where('enseignant.nom', $filters['teacher']);
        }

        if (!empty($filters['date_debut'])) {
            $this->where('rattrapage.date_rattrapage >=', $filters['date_debut']);
        }

        if (!empty($filters['date_fin'])) {
            $this->where('rattrapage.date_rattrapage <=', $filters['date_fin']);
        }

        $this->orderBy('rattrapage.date_rattrapage', 'DESC');

        $results = $this->paginate($perPage, 'default');

        foreach ($results as &$row) {
            $row['duree_formatee'] = $this->formatDuree($row['duree_minutes']);
        }

        return $results;
    }

    /**
     * Récupère un rattrapage avec tous ses détails
     */
    public function getRattrapageWithDetails(int $id)
    {
        $this->select('
            rattrapage.*,
            ds.date_ds,
            ds.type_exam as ds_type_exam,
            ressource.coderessource,
            ressource.nomressource,
            enseignant.code as enseignant_code,
            enseignant.nom as enseignant_nom,
            enseignant.prenom as enseignant_prenom,
            semestre.id_semestre,
            semestre.code as semestre_code
        ');
        
        $this->join('ds', 'ds.id_ds = rattrapage.id_ds');
        $this->join('ressource', 'ressource.coderessource = ds.coderessource');
        $this->join('enseignant', 'enseignant.code = rattrapage.code');
        $this->join('semestre', 'semestre.id_semestre = ds.id_semestre');

        $result = $this->where('rattrapage.id_rattrapage', $id)->first();

        if ($result) {
            $result['duree_formatee'] = $this->formatDuree($result['duree_minutes']);
            $result['enseignant_complet'] = $result['enseignant_prenom'] . ' ' . $result['enseignant_nom'];
        }

        return $result;
    }

    /**
     * Insère un nouveau rattrapage
     */
    public function insertRattrapage(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Met à jour l'état d'un rattrapage
     */
    public function updateEtat(int $id, string $etat)
    {
        return $this->update($id, ['etat' => $etat]);
    }

    /**
     * Met à jour un rattrapage
     */
    public function updateRattrapage(int $id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Formate la durée en HH:MM ou 1h30
     */
    private function formatDuree(int $minutes): string
    {
        $heures = floor($minutes / 60);
        $mins = $minutes % 60;
        return $heures . 'h' . sprintf('%02d', $mins);
    }
}
