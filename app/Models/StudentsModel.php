<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentsModel extends Model
{
    protected $table = 'etudiant';
    protected $primaryKey = 'code';
    protected $allowedFields = ['nom', 'prenom', 'email', 'classe', 'id_semestre'];

    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    
    public function getPaginatedStudentsByDSiD(int $ds_id, int $perPage, ?string $keyword = null) 
    {
        $this->select('
            etudiant.code as id, 
            etudiant.nom, 
            etudiant.prenom, 
            etudiant.classe,
            (CASE WHEN a.code IS NOT NULL THEN 1 ELSE 0 END) as absent,
            COALESCE(a.absenceJustifie, 0) as justifie
        ')->join('absence a', "etudiant.code = a.code")->where('a.id_ds', $ds_id);

        if ($keyword) {
            $this->groupStart()
                 ->like('etudiant.nom', $keyword)
                 ->orLike('etudiant.prenom', $keyword)
                 ->orLike('etudiant.classe', $keyword)
                 ->groupEnd();
        }

        $this->orderBy("etudiant.nom", "asc");

        $results = $this->paginate($perPage, 'default');

        return $results;
    }

    /**
     * Récupère les étudiants avec leur statut d'absence pour un DS spécifique
     */
    public function getStudentsWithAbsenceForDs(int $idDs, int $perPage, ?string $keyword = null): array
    {
        $this->select('
            etudiant.code as id, 
            etudiant.nom, 
            etudiant.prenom, 
            etudiant.classe,
            (CASE WHEN a.code IS NOT NULL THEN 1 ELSE 0 END) as absent,
            COALESCE(a.absencejustifie, 0) as justifie
        ');

        $this->join('absence a', 'etudiant.code = a.code AND a.id_ds = ' . $idDs, 'left');

        if ($keyword) {
            $this->groupStart()
                 ->like('etudiant.nom', $keyword)
                 ->orLike('etudiant.prenom', $keyword)
                 ->orLike('etudiant.classe', $keyword)
                 ->groupEnd();
        }

        $this->orderBy('etudiant.nom', 'ASC');
        $this->orderBy('etudiant.prenom', 'ASC');

        $results = $this->paginate($perPage, 'default');

        foreach ($results as &$row) {
            $row['absent'] = (bool) $row['absent'];
            $row['justifie'] = (bool) $row['justifie'];
        }

        return $results;
    }
}
