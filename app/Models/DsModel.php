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

    // ========================================
    // Méthodes auxiliaires pour le contrôleur DS
    // ========================================

    /**
     * Récupère tous les semestres pour un dropdown
     */
    public function getAllSemestersForDropdown(): array
    {
        $db = \Config\Database::connect();
        $semesters = $db->table('semestre')->select('code')->orderBy('code', 'ASC')->get()->getResultArray();
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
        $db = \Config\Database::connect();
        $semesters = $db->table('semestre')->select('code')->get()->getResultArray();
        return array_column($semesters, 'code');
    }

    /**
     * Récupère l'ID d'un semestre par son code
     */
    public function getSemesterIdByCode(string $code): ?int
    {
        $db = \Config\Database::connect();
        $semester = $db->table('semestre')->where('code', $code)->get()->getRowArray();
        return $semester ? (int) $semester['id_semestre'] : null;
    }

    /**
     * Récupère toutes les ressources pour un dropdown
     */
    public function getAllResourcesForDropdown(): array
    {
        $db = \Config\Database::connect();
        $resources = $db->table('ressource')->select('coderessource, nomressource')->orderBy('coderessource', 'ASC')->get()->getResultArray();
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
        $db = \Config\Database::connect();
        $resources = $db->table('ressource')->select('coderessource')->get()->getResultArray();
        return array_column($resources, 'coderessource');
    }

    /**
     * Récupère les ressources par semestre pour un dropdown
     */
    public function getResourcesBySemesterForDropdown(string $semesterCode): array
    {
        $db = \Config\Database::connect();
        $resources = $db->table('ressource')
                        ->select('ressource.coderessource, ressource.nomressource')
                        ->join('ds', 'ds.coderessource = ressource.coderessource')
                        ->join('semestre', 'semestre.id_semestre = ds.id_semestre')
                        ->where('semestre.code', $semesterCode)
                        ->groupBy('ressource.coderessource, ressource.nomressource')
                        ->get()->getResultArray();
        
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
        $db = \Config\Database::connect();
        $resources = $db->table('ressource')
                        ->select('ressource.coderessource, ressource.nomressource')
                        ->join('ds', 'ds.coderessource = ressource.coderessource')
                        ->join('semestre', 'semestre.id_semestre = ds.id_semestre')
                        ->where('semestre.code', $semesterCode)
                        ->groupBy('ressource.coderessource, ressource.nomressource')
                        ->get()->getResultArray();
        
        if (empty($resources)) {
            $resources = $db->table('ressource')->select('coderessource, nomressource')->get()->getResultArray();
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

    /**
     * Récupère tous les professeurs pour un dropdown
     */
    public function getAllTeachersForDropdown(): array
    {
        $db = \Config\Database::connect();
        $teachers = $db->table('enseignant')->select('nom, prenom')->orderBy('nom', 'ASC')->get()->getResultArray();
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
        $db = \Config\Database::connect();
        $teachers = $db->table('enseignant')->select('nom')->get()->getResultArray();
        return array_column($teachers, 'nom');
    }

    /**
     * Récupère le code d'un professeur par son nom
     */
    public function getTeacherCodeByName(string $name): ?string
    {
        $db = \Config\Database::connect();
        $teacher = $db->table('enseignant')->where('nom', $name)->get()->getRowArray();
        return $teacher ? $teacher['code'] : null;
    }

    /**
     * Récupère les professeurs par ressource pour un dropdown
     */
    public function getTeachersByResourceForDropdown(string $resourceCode): array
    {
        $db = \Config\Database::connect();
        $teachers = $db->table('enseignant')
                       ->select('enseignant.nom, enseignant.prenom')
                       ->join('ds', 'ds.codeenseignant = enseignant.code')
                       ->where('ds.coderessource', $resourceCode)
                       ->groupBy('enseignant.code, enseignant.nom, enseignant.prenom')
                       ->get()->getResultArray();
        
        if (empty($teachers)) {
            return $this->getAllTeachersForDropdown();
        }
        
        $dropdown = [];
        foreach ($teachers as $teacher) {
            $dropdown[$teacher['nom']] = $teacher['prenom'] . ' ' . $teacher['nom'];
        }
        return $dropdown;
    }

    /**
     * Récupère les professeurs par ressource pour AJAX
     */
    public function getTeachersByResourceForAjax(string $resourceCode): array
    {
        $db = \Config\Database::connect();
        $teachers = $db->table('enseignant')
                       ->select('enseignant.nom, enseignant.prenom')
                       ->join('ds', 'ds.codeenseignant = enseignant.code')
                       ->where('ds.coderessource', $resourceCode)
                       ->groupBy('enseignant.code, enseignant.nom, enseignant.prenom')
                       ->get()->getResultArray();
        
        if (empty($teachers)) {
            $teachers = $db->table('enseignant')->select('nom, prenom')->get()->getResultArray();
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

    /**
     * Récupère les étudiants avec leur statut d'absence pour un DS spécifique
     */
    public function getStudentsWithAbsenceForDs(int $idDs, int $perPage, ?string $keyword = null): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('etudiant');
        
        $builder->select('
            etudiant.code as id, 
            etudiant.nom, 
            etudiant.prenom, 
            etudiant.classe,
            (CASE WHEN a.code IS NOT NULL THEN 1 ELSE 0 END) as absent,
            COALESCE(a.absencejustifie, 0) as justifie
        ');

        $builder->join('absence a', 'etudiant.code = a.code AND a.id_ds = ' . $idDs, 'left');

        if ($keyword) {
            $builder->groupStart()
                    ->like('etudiant.nom', $keyword)
                    ->orLike('etudiant.prenom', $keyword)
                    ->orLike('etudiant.classe', $keyword)
                    ->groupEnd();
        }

        $builder->orderBy('etudiant.nom', 'ASC');
        $builder->orderBy('etudiant.prenom', 'ASC');

        // Pagination manuelle
        $total = $builder->countAllResults(false);
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $offset = ($page - 1) * $perPage;
        
        $results = $builder->limit($perPage, $offset)->get()->getResultArray();

        foreach ($results as &$row) {
            $row['absent'] = (bool) $row['absent'];
            $row['justifie'] = (bool) $row['justifie'];
        }

        // Stocker les infos de pagination
        $this->pager = \Config\Services::pager();
        $this->pager->setPath('DS/detail/' . $idDs);
        $this->pager->makeLinks($page, $perPage, $total, 'default_full', 0, 'default');

        return $results;
    }
}
