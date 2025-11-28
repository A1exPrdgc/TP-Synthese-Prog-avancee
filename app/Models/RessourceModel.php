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

    public function getAllResourcesBySemesters($semesterCode)
    {
        return $this->select('ressource.coderessource, ressource.nomressource')
                    ->join('ds d', 'd.coderessource = ressource.coderessource')
                    ->join('semestre s', 's.id_semestre = d.id_semestre')
                    ->where('s.code', $semesterCode)
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
                'nom' => $resource['nomressource']
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
