<?php

namespace App\Models;

use CodeIgniter\Model;

class RattrapantModel extends Model
{
    protected $table = 'rattrapants';
    protected $primaryKey = ['id_rattrapage', 'codeEtudiant']; // PK composite

    protected $allowedFields = ['id_rattrapage', 'codeetudiant'];
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    // Ajouter un rattrapant
    public function addRattrapant(int $idRattrapage, string $codeEtudiant): bool
    {
        return $this->insert([
            'id_rattrapage' => $idRattrapage,
            'codeetudiant' => $codeEtudiant,  // noter la casse minuscule
        ], false);
    }

    // Récupérer tous les étudiants rattrapants d'un rattrapage
    public function getRattrapantsByRattrapage(int $idRattrapage): array
    {
        return $this->where('id_rattrapage', $idRattrapage)->findAll();
    }

    // Supprimer un rattrapant précis
    public function removeRattrapant(int $idRattrapage, string $codeEtudiant): bool
    {
        return $this->where('id_rattrapage', $idRattrapage)
            ->where('codeetudiant', $codeEtudiant)
            ->delete();
    }
}
