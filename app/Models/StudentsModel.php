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
    
    /**
     * Récupère les étudiants absents pour un DS spécifique
     */
    public function getPaginatedStudentsByDSiD(int $ds_id, int $perPage, ?string $keyword = null) 
    {
        $this->select('
            etudiant.code as id, 
            etudiant.nom, 
            etudiant.prenom, 
            etudiant.classe,
            etudiant.email,
            1 as absent,
            COALESCE(a.absenceJustifie, 0) as justifie,
            COALESCE(a.rattrape, 0) as rattrape
        ')
        ->join('absence a', 'etudiant.code = a.code')
        ->where('a.id_ds', $ds_id);

        if ($keyword) {
            $this->groupStart()
                 ->like('etudiant.nom', $keyword)
                 ->orLike('etudiant.prenom', $keyword)
                 ->orLike('etudiant.classe', $keyword)
                 ->groupEnd();
        }

        $this->orderBy('etudiant.nom', 'asc');

        $results = $this->paginate($perPage, 'default');

        foreach ($results as &$row) {
            $row['absent'] = (bool) $row['absent'];
            $row['justifie'] = (bool) $row['justifie'];
            $row['rattrape'] = (bool) $row['rattrape'];
        }

        return $results;
    }

    /**
     * Récupère les étudiants avec leur statut d'absence pour un DS spécifique (tous les étudiants)
     */
    public function getStudentsWithAbsenceForDs(int $idDs, int $perPage, ?string $keyword = null): array
    {
        $this->select('
            etudiant.code as id, 
            etudiant.nom, 
            etudiant.prenom, 
            etudiant.classe,
            etudiant.email,
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

        $this->orderBy('absent', 'DESC');
        $this->orderBy('etudiant.nom', 'ASC');
        $this->orderBy('etudiant.prenom', 'ASC');

        $results = $this->paginate($perPage, 'default');

        foreach ($results as &$row) {
            $row['absent'] = (bool) $row['absent'];
            $row['justifie'] = (bool) $row['justifie'];
        }

        return $results;
    }

    public function getStudentsWithAbsenceForDsBySemester(string $semester_code, int $idDs, int $perPage, ?string $keyword = null): array
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
        $this->join('semestre s', 's.id_semestre = etudiant.id_semestre')
             ->where('s.code', $semester_code);

        if ($keyword) {
            $this->groupStart()
                 ->like('etudiant.nom', $keyword)
                 ->orLike('etudiant.prenom', $keyword)
                 ->orLike('etudiant.classe', $keyword)
                 ->groupEnd();
        }

        $this->orderBy('absent', 'DESC');
        $this->orderBy('etudiant.nom', 'ASC');
        $this->orderBy('etudiant.prenom', 'ASC');

        $results = $this->paginate($perPage, 'default');

        foreach ($results as &$row) {
            $row['absent'] = (bool) $row['absent'];
            $row['justifie'] = (bool) $row['justifie'];
        }

        return $results;
    }

    /**
     * Récupère les étudiants par semestre pour le formulaire d'ajout DS
     */
    public function getPaginatedStudentsBySemester(string $semester_code, int $perPage, ?string $keyword = null) 
    {
        $this->select('
            etudiant.code as id, 
            etudiant.nom, 
            etudiant.prenom, 
            etudiant.classe,
            etudiant.email
        ')
        ->join('semestre s', 's.id_semestre = etudiant.id_semestre')
        ->where('s.code', $semester_code);
        
        if ($keyword) {
            $this->groupStart()
                 ->like('etudiant.nom', $keyword)
                 ->orLike('etudiant.prenom', $keyword)
                 ->orLike('etudiant.classe', $keyword)
                 ->groupEnd();
        }

        $this->orderBy('etudiant.nom', 'asc');
        $this->orderBy('etudiant.prenom', 'asc');

        $results = $this->paginate($perPage, 'default');

        // Initialiser absent et justifie à false pour le formulaire d'ajout
        foreach ($results as &$row) {
            $row['absent'] = false;
            $row['justifie'] = false;
        }


        return $results;
    }


/**
 * Récupère uniquement les étudiants absents pour un DS spécifique
 */
public function getAbsentStudentsForDs(int $idDs, int $perPage, ?string $keyword = null): array
{
    $this->select('
        etudiant.code as id, 
        etudiant.nom, 
        etudiant.prenom, 
        etudiant.classe,
        1 as absent,
        COALESCE(a.absencejustifie, 0) as justifie
    ');

    // Jointure obligatoire, car on ne veut que les absents
    $this->join('absence a', 'etudiant.code = a.code AND a.id_ds = ' . $idDs, 'inner');

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
        $row['absent'] = true; // obligatoirement absent
        $row['justifie'] = (bool) $row['justifie'];
    }

    return $results;
}



    /**
     * Récupère les étudiants par semestre pour AJAX
     */
    public function getStudentsBySemesterForAjax(string $semester_code, ?string $keyword = null): array
    {
        $builder = $this->select('
            etudiant.code as id, 
            etudiant.nom, 
            etudiant.prenom, 
            etudiant.classe
        ')
        ->join('semestre s', 's.id_semestre = etudiant.id_semestre')
        ->where('s.code', $semester_code);
        
        if ($keyword) {
            $this->groupStart()
                 ->like('etudiant.nom', $keyword)
                 ->orLike('etudiant.prenom', $keyword)
                 ->orLike('etudiant.classe', $keyword)
                 ->groupEnd();
        }

        $this->orderBy('etudiant.nom', 'asc');
        $this->orderBy('etudiant.prenom', 'asc');

        return $this->findAll();
    }

    public function getPaginatedAbsentStudentsAndRattrapeByDSiD(int $ds_id, int $perPage, ?string $keyword = null) 
    {
        $this->select('
            etudiant.code as id, 
            etudiant.nom, 
            etudiant.prenom, 
            etudiant.classe,
            etudiant.email,
            1 as absent,
            COALESCE(a.absenceJustifie, 0) as justifie
        ')
        ->join('absence a', 'etudiant.code = a.code')
        ->where('a.id_ds', $ds_id)
        ->where('a.rattrape', 1);

        if ($keyword) {
            $this->groupStart()
                 ->like('etudiant.nom', $keyword)
                 ->orLike('etudiant.prenom', $keyword)
                 ->orLike('etudiant.classe', $keyword)
                 ->groupEnd();
        }

        $this->orderBy('etudiant.nom', 'asc');

        $results = $this->paginate($perPage, 'default');

        foreach ($results as &$row) {
            $row['absent'] = (bool) $row['absent'];
            $row['justifie'] = (bool) $row['justifie'];
        }

        return $results;
    }

    public function getNameByCode(string $code): ?string
    {
        $student = $this->where('code', $code)->first();
        return $student ? $student['nom'] . ' ' . $student['prenom'] : null;
    }
}
