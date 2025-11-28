<?php

namespace App\Models;

use CodeIgniter\Model;

class TeachersModel extends Model
{
    protected $table = 'enseignant';
    protected $primaryKey = 'code';
    protected $allowedFields = ['code', 'nom', 'prenom', 'email', 'password', 'fonction', 'reset_token', 'reset_expires', 'photo'];
    protected $useAutoIncrement = false;

    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    public function getAllTeachersByResources($resourceId)
    {
        return $this->select('enseignant.code, enseignant.nom')
                    ->join('ds d', 'd.codeenseignant = enseignant.code')
                    ->where('d.coderessource', $resourceId)
                    ->findAll();
    }

    public function getRole()
    {
        $fonction = session()->get('code');

        return $this->select('fonction')
                    ->where('code', $fonction)
                    ->findAll();
    }

    /**
     * Récupère tous les professeurs pour un dropdown
     */
    public function getAllTeachersForDropdown(): array
    {
        $teachers = $this->select('nom, prenom')->orderBy('nom', 'ASC')->findAll();
        $dropdown = [];
        foreach ($teachers as $teacher) {
            $dropdown[$teacher['nom']] = $teacher['prenom'] . ' ' . $teacher['nom'];
        }
        return $dropdown;
    }

    /**
     * Récupère tous les noms de professeurs pour la validation
     */
    public function getAllTeacherNames(): array
    {
        $teachers = $this->select('nom')->findAll();
        return array_column($teachers, 'nom');
    }

    /**
     * Récupère le code d'un professeur par son nom
     */
    public function getCodeByName(string $name): ?string
    {
        $teacher = $this->where('nom', $name)->first();
        return $teacher ? $teacher['code'] : null;
    }

    /**
     * Récupère les professeurs par ressource pour un dropdown
     */
    public function getTeachersByResourceForDropdown(string $resourceCode): array
    {
        $teachers = $this->getAllTeachersByResources($resourceCode);
        
        if (empty($teachers)) {
            return $this->getAllTeachersForDropdown();
        }
        
        $dropdown = [];
        foreach ($teachers as $teacher) {
            $fullName = $this->where('code', $teacher['code'])->first();
            $dropdown[$teacher['nom']] = ($fullName['prenom'] ?? '') . ' ' . $teacher['nom'];
        }
        return $dropdown;
    }

    /**
     * Récupère les professeurs par ressource pour AJAX
     */
    public function getTeachersByResourceForAjax(string $resourceCode): array
    {
        $teachers = $this->getAllTeachersByResources($resourceCode);
        
        if (empty($teachers)) {
            $teachers = $this->findAll();
        }
        
        $result = [];
        foreach ($teachers as $teacher) {
            $result[] = [
                'nom' => $teacher['nom'],
                'prenom' => $teacher['prenom'] ?? '',
                'fullname' => ($teacher['prenom'] ?? '') . ' ' . $teacher['nom']
            ];
        }
        return $result;
    }

    public function getEmailByCode(string $code): ?string
    {
        $teacher = $this->where('code', $code)->first();
        return $teacher ? $teacher['email'] : null;
    }
}
