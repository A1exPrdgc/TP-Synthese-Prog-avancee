<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsentModel extends Model
{
    protected $table = 'absence';
    protected $primaryKey = ['id_ds', 'code'];
    protected $allowedFields = ['id_ds', 'code', 'absencejustifie'];

    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    /**
     * Marque un étudiant comme absent pour un DS
     */
    public function markAbsent(int $idDs, string $codeEtudiant, int $justified = 0): bool
    {
        $data = [
            'id_ds' => $idDs,
            'code' => $codeEtudiant,
            'absencejustifie' => $justified
        ];

        return $this->insert($data) !== false;
    }

    /**
     * Supprime l'absence d'un étudiant pour un DS
     */
    public function removeAbsent(int $idDs, string $codeEtudiant): bool
    {
        return $this->where('id_ds', $idDs)
                    ->where('code', $codeEtudiant)
                    ->delete();
    }

    /**
     * Met à jour le statut de justification
     */
    public function updateJustification(int $idDs, string $codeEtudiant, int $justified): bool
    {
        return $this->where('id_ds', $idDs)
                    ->where('code', $codeEtudiant)
                    ->set('absencejustifie', $justified)
                    ->update();
    }

    /**
     * Vérifie si un étudiant est absent pour un DS
     */
    public function isAbsent(int $idDs, string $codeEtudiant): bool
    {
        return $this->where('id_ds', $idDs)
                    ->where('code', $codeEtudiant)
                    ->countAllResults() > 0;
    }

    /**
     * Récupère toutes les absences pour un DS
     */
    public function getAbsencesByDs(int $idDs): array
    {
        return $this->where('id_ds', $idDs)->findAll();
    }

    /**
     * Compte les absences pour un DS
     */
    public function countAbsencesByDs(int $idDs): int
    {
        return $this->where('id_ds', $idDs)->countAllResults();
    }

    /**
     * Compte les absences justifiées pour un DS
     */
    public function countJustifiedAbsencesByDs(int $idDs): int
    {
        return $this->where('id_ds', $idDs)
                    ->where('absencejustifie', 1)
                    ->countAllResults();
    }
}
