<?php

namespace App\Models;

use CodeIgniter\Model;

class DsModel extends Model
{
    protected $table = 'ds';
    protected $primaryKey = 'id_ds';
    protected $allowedFields = ['id_semestre', 'date_ds', 'duree_minutes', 'type_exam', 'coderessource', 'codeenseignant'];

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
            $row['etat'] = (isset($row['nb_absences']) && $row['nb_absences'] > 0) ? 'Rattraper' : 'Terminé';
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
}
