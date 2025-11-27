<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentsModel extends Model
{
    protected $table = 'etudiant';
    protected $primaryKey = 'code';
    protected $allowedFields = ['nom', 'prenom', 'email', 'classe'];

    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    public function getPaginatedStudents(int $perPage, string $keyword = null)
    {
        $this->select('
            etudiant.code as id, 
            etudiant.nom, 
            etudiant.prenom, 
            etudiant.classe,
            (CASE WHEN a.code IS NOT NULL THEN 1 ELSE 0 END) as absent,
            COALESCE(a.absencejustifie, 0) as justifie
        ');

        $conditionJoin = "etudiant.code = a.code";
        $this->join('absence a', $conditionJoin, 'left');

        if ($keyword) {
            $this->groupStart()
                 ->like('etudiant.nom', $keyword)
                 ->orLike('etudiant.prenom', $keyword)
                 ->orLike('etudiant.classe', $keyword)
                 ->groupEnd();
        }

        $this->orderBy("etudiant.nom", "asc");
        $this->orderBy("etudiant.prenom", "asc");

        $results = $this->paginate($perPage, 'default');

        foreach ($results as &$row) {
            if (is_array($row)) {
                $row['absent'] = (bool) $row['absent'];
                $row['justifie'] = (bool) $row['justifie'];
            } else {
                $row->absent = (bool) $row->absent;
                $row->justifie = (bool) $row->justifie;
            }
        }

        return $results;
    }
}
