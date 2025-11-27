<?php

namespace App\Models;

use CodeIgniter\Model;

class SemestersModel extends Model
{
    protected $table = 'semestre';
    protected $primaryKey = 'id_semestre';
    protected $allowedFields = ['code', 'annee'];

    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    public function getAllSemesters()
    {
        return $this->select('code')->findAll();
    }

    /**
     * Récupère tous les semestres pour un dropdown
     */
    public function getAllSemestersForDropdown(): array
    {
        $semesters = $this->select('code')->orderBy('code', 'ASC')->findAll();
        $dropdown = [];
        foreach ($semesters as $semester) {
            $dropdown[$semester['code']] = $semester['code'];
        }
        return $dropdown;
    }

    /**
     * Récupère tous les codes de semestres pour la validation
     */
    public function getAllSemesterCodes(): array
    {
        $semesters = $this->select('code')->findAll();
        return array_column($semesters, 'code');
    }

    /**
     * Récupère l'ID d'un semestre par son code
     */
    public function getIdByCode(string $code): ?int
    {
        $semester = $this->where('code', $code)->first();
        return $semester ? (int) $semester['id_semestre'] : null;
    }
}
