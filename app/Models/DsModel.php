<?php

namespace App\Models;

use CodeIgniter\Model;

class DsModel extends Model
{
    protected $table = 'ds';
    protected $primaryKey = 'id_ds';
    protected $allowedFields = ['id_semestre', 'date_ds', 'duree_minutes', 'type_exam', 'coderessource', 'codeenseignant', 'etat'];

    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    /**
     * Récupère la liste des DS avec pagination et filtres
     */
    public function getPaginatedDS(int $perPage, array $filters = [])
    {
        $this->select('
            ds.id_ds,
            ressource.coderessource,
            ressource.nomressource,
            enseignant.nom as enseignant_nom,
            enseignant.prenom as enseignant_prenom,
            ds.date_ds,
            ds.duree_minutes,
            ds.type_exam,
            semestre.code as semestre_code,
            ds.etat,  -- Ajouté ici
            (SELECT COUNT(*) FROM absence WHERE absence.id_ds = ds.id_ds) as nb_absences
        ');
        
        $this->join('ressource', 'ressource.coderessource = ds.coderessource');
        $this->join('enseignant', 'enseignant.code = ds.codeenseignant');
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
            $this->where('ds.date_ds >=', $filters['date_debut']);
        }

        if (!empty($filters['date_fin'])) {
            $this->where('ds.date_ds <=', $filters['date_fin']);
        }

        $this->orderBy('ds.date_ds', 'DESC');

        $results = $this->paginate($perPage, 'default');

        foreach ($results as &$row) {
            $row['duree_formatee'] = $this->formatDuree($row['duree_minutes']);
        }

        return $results;
    }

    /**
     * Récupère un DS avec tous ses détails
     */
    public function getDsWithDetails(int $id)
    {
        $this->select('
            ds.id_ds,
            ds.date_ds,
            ds.duree_minutes,
            ds.type_exam,
            ressource.coderessource,
            ressource.nomressource,
            enseignant.code as enseignant_code,
            enseignant.nom as enseignant_nom,
            enseignant.prenom as enseignant_prenom,
            semestre.id_semestre,
            semestre.code as semestre_code
        ');
        
        $this->join('ressource', 'ressource.coderessource = ds.coderessource');
        $this->join('enseignant', 'enseignant.code = ds.codeenseignant');
        $this->join('semestre', 'semestre.id_semestre = ds.id_semestre');

        $result = $this->where('ds.id_ds', $id)->first();

        if ($result) {
            $result['duree_formatee'] = $this->formatDuree($result['duree_minutes']);
            $result['enseignant_complet'] = $result['enseignant_prenom'] . ' ' . $result['enseignant_nom'];
        }

        return $result;
    }

    /**
     * Crée un nouveau DS
     */
    public function createDs(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Formate la durée en HH:MM
     */
    private function formatDuree(int $minutes): string
    {
        $heures = floor($minutes / 60);
        $mins = $minutes % 60;
        return sprintf('%02d:%02d', $heures, $mins);
    }

    public function setEtat(int $idDs, string $etat)
    {
        if (empty($etat) || !is_string($etat)) {
            throw new \InvalidArgumentException('L\'état ne peut pas être vide et doit être une chaîne.');
        }

        if (!$idDs) {
            return false;
        }

        return $this->update($idDs, ['etat' => $etat]);
    }

    public function updateEtatByRattrapageDate()
    {
        $now = date('Y-m-d');
    
        // 1) DS dont le rattrapage est passé → état TERMINE
        $sqlTermine = "SELECT ds.id_ds
                       FROM ds
                       JOIN rattrapage ON ds.id_ds = rattrapage.id_ds
                       WHERE rattrapage.date_rattrapage < ?";
    
        $queryTermine = $this->db->query($sqlTermine, [$now]);
        $dsTermines = $queryTermine->getResultArray();
    
        foreach ($dsTermines as $row) {
            $this->setEtat($row['id_ds'], 'TERMINE');
        }
    
        // 2) DS avec rattrapage futur → état PREVU
        $sqlPrevu = "SELECT ds.id_ds
                     FROM ds
                     JOIN rattrapage ON ds.id_ds = rattrapage.id_ds
                     WHERE rattrapage.date_rattrapage >= ?";
    
        $queryPrevu = $this->db->query($sqlPrevu, [$now]);
        $dsPrevus = $queryPrevu->getResultArray();
    
        foreach ($dsPrevus as $row) {
            $this->setEtat($row['id_ds'], 'PREVU');
        }
    }


    /**
     * Vérifie et corrige les DS sans absences qui ne sont pas en état REFUSE
     */
    public function updateEtatByAbsences()
    {
        $sql = "SELECT ds.id_ds
                FROM ds 
                LEFT JOIN absence ON absence.id_ds = ds.id_ds
                WHERE ds.etat != 'REFUSE' 
                AND ds.etat IS NOT NULL
                GROUP BY ds.id_ds 
                HAVING COUNT(absence.id_ds) = 0";
    
        $query = $this->db->query($sql);
        $dsSansAbsences = $query->getResultArray();
    
        foreach ($dsSansAbsences as $row) {
            $this->setEtat($row['id_ds'], 'REFUSE');
        }
    }
    
}
