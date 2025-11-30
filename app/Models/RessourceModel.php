<?php

namespace App\Models;

use CodeIgniter\Model;

class RessourceModel extends Model
{
    protected $table = 'ressource';
    protected $primaryKey = 'coderessource';
    protected $allowedFields = ['nomressource'];

    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    public function getAllResourcesBySemesters(string $semesterCode)
    {
        // Extraire numéro semestre S1 → 1, S2 → 2, etc
        $numSemestre = substr($semesterCode, 1);

        return $this->select('ressource.coderessource, ressource.nomressource')
                    ->like('ressource.coderessource', 'R' . $numSemestre . '.', 'after') // filtre par préfixe R1., R2., etc
                    ->findAll();
    }


    /**
     * Récupère toutes les ressources pour un dropdown
     */
    public function getAllResourcesForDropdown(): array
    {
        $resources = $this->select('coderessource, nomressource')->orderBy('coderessource', 'ASC')->findAll();
        $dropdown = [];
        foreach ($resources as $resource) {
            $dropdown[$resource['coderessource']] = $resource['nomressource'];
        }
        return $dropdown;
    }

    /**
     * Récupère tous les codes de ressources pour la validation
     */
    public function getAllResourceCodes(): array
    {
        $resources = $this->select('coderessource')->findAll();
        return array_column($resources, 'coderessource');
    }

    /**
     * Récupère les ressources par semestre pour un dropdown
     */
    public function getResourcesBySemesterForDropdown(string $semesterCode): array
    {
        $resources = $this->getAllResourcesBySemesters($semesterCode);
        
        if (empty($resources)) {
            return $this->getAllResourcesForDropdown();
        }
        
        $dropdown = [];
        foreach ($resources as $resource) {
            // NE PAS concaténer ici, juste nom de ressource brut
            $dropdown[$resource['coderessource']] = $resource['nomressource'];
        }
        return $dropdown;
    }


    /**
     * Récupère les ressources par semestre pour AJAX
     */
    public function getResourcesBySemesterForAjax(string $semesterCode): array
    {
        $resources = $this->getAllResourcesBySemesters($semesterCode);
        
        if (empty($resources)) {
            $resources = $this->findAll();
        }
        
        $result = [];
        foreach ($resources as $resource) {
            $result[] = [
                'code' => $resource['coderessource'],
                'nom' => $resource['nomressource'], // Pas de concaténation ici non plus
            ];
        }
        return $result;
    
    }
    public function getNameByCode(string $code): ?string
    {
        $resource = $this->where('coderessource', $code)->first();
        return $resource ? $resource['nomressource'] : null;
    }
}
