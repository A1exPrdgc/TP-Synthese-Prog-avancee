<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentsModel;
use App\Models\SemestersModel;
use App\Models\RessourceModel;
use App\Models\TeachersModel;
use App\Models\DsModel;
use App\Models\AbsentModel;

class DS extends BaseController
{
    protected $dsModel;
    protected $semesterModel;
    protected $resourceModel;
    protected $teacherModel;
    protected $studentModel;
    protected $absentModel;

    public function __construct()
    {
        helper(['form']);
        
        $this->dsModel = new DsModel();
        $this->semesterModel = new SemestersModel();
        $this->resourceModel = new RessourceModel();
        $this->teacherModel = new TeachersModel();
        $this->studentModel = new StudentsModel();
        $this->absentModel = new AbsentModel();
        
        session()->set('role', $this->teacherModel->getRole());
    }

    /**
     * Liste des DS avec filtres
     */
    public function index()
    {
        $this->dsModel->updateEtatByAbsences();
        $this->dsModel->updateEtatByRattrapageDate();
        $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);
        
        $filters = [
            'keyword' => $this->request->getGet('keyword') ?? '',
            'resource' => $this->request->getGet('resource') ?? '',
            'semester' => $this->request->getGet('semester') ?? '',
            'teacher' => $this->request->getGet('teacher') ?? '',
            'date_debut' => $this->request->getGet('date_debut') ?? '',
            'date_fin' => $this->request->getGet('date_fin') ?? ''
        ];

        $data['dsList'] = $this->dsModel->getPaginatedDS($perPage, $filters);
        $data['pager'] = $this->dsModel->pager;
        $data['filters'] = $filters;

        $data['semesters'] = $this->semesterModel->getAllSemestersForDropdown();
        $data['resources'] = $this->resourceModel->getAllResourcesForDropdown();
        $data['teachers'] = $this->teacherModel->getAllTeachersForDropdown();

        return view('ds/index', $data);
    }

    /**
     * Détail d'un DS
     */
    public function detail($id = null)
    {
        if (!$id) {
            return redirect()->to('ds')->with('error', 'ID du DS non spécifié');
        }

        $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);
        $keyword = $this->request->getGet('keyword') ?? '';

        $ds = $this->dsModel->getDsWithDetails($id);
        
        if (!$ds) {
            return redirect()->to('ds')->with('error', 'DS non trouvé');
        }

        $data['students'] = $this->studentModel->getPaginatedStudentsBySemester($ds['semestre_code'], $perPage, $keyword);
        $data['pager'] = $this->studentModel->pager;
        $data['keyword'] = $keyword;
        $data['ds'] = $ds;

        return view('ds/detail', $data);
    }

    /**
     * Formulaire d'ajout d'un DS
     */
    public function ajout()
    {
        $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);
        $keyword = $this->request->getGet('keyword') ?? '';
        $semester = $this->request->getGet('semester') ?? 'S1';

        $data['keyword'] = $keyword;

        $data['students'] = $this->studentModel->getPaginatedStudentsBySemester($semester, $perPage, $keyword);
        $data['pager'] = $this->studentModel->pager;

        $data['semesters'] = $this->semesterModel->getAllSemestersForDropdown();
        $selectedSemester = array_key_first($data['semesters']) ?? '';

        $data['resources'] = $this->resourceModel->getResourcesBySemesterForDropdown($selectedSemester);
        $selectedResource = array_key_first($data['resources']) ?? '';

        $data['teachers'] = $this->teacherModel->getTeachersByResourceForDropdown($selectedResource);

        $data['types'] = ['MACHINE' => 'Machine', 'PAPIER' => 'Papier', 'ORAL' => 'Oral'];

        $data['semester'] = [$selectedSemester];
        $data['resource'] = [$selectedResource];
        $data['teacher'] = [array_key_first($data['teachers']) ?? ''];
        $data['type'] = [array_key_first($data['types']) ?? ''];
        
        $data['validation'] = \Config\Services::validation();

        return view('ds/ajout', $data);
    }

    /**
     * Sauvegarde d'un nouveau DS
     */
    public function save()
    {
        $validSemesters = $this->semesterModel->getAllSemesterCodes();
        $validResources = $this->resourceModel->getAllResourceCodes();
        $validTeachers = $this->teacherModel->getAllTeacherNames();
        $validTypes = ['MACHINE', 'PAPIER', 'ORAL'];

        $rules = [
            'semester' => 'required|in_list[' . implode(',', $validSemesters) . ']',
            'resource' => 'required|in_list[' . implode(',', $validResources) . ']',
            'teacher' => 'required|in_list[' . implode(',', $validTeachers) . ']',
            'date' => 'required|valid_date',
            'type' => 'required|in_list[' . implode(',', $validTypes) . ']',
            'duration' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $semesterCode = $this->request->getPost('semester');
        $resourceCode = $this->request->getPost('resource');
        $teacherName = $this->request->getPost('teacher');
        $date = $this->request->getPost('date');
        $type = $this->request->getPost('type');
        $duration = $this->request->getPost('duration');

        list($heures, $minutes) = explode(':', $duration);
        $dureeMinutes = ($heures * 60) + $minutes;

        $semesterId = $this->semesterModel->getIdByCode($semesterCode);
        $teacherCode = $this->teacherModel->getCodeByName($teacherName);

        if (!$semesterId || !$teacherCode) {
            return redirect()->back()->withInput()->with('error', 'Données invalides');
        }

        $dsId = $this->dsModel->createDs([
            'id_semestre' => $semesterId,
            'date_ds' => $date,
            'duree_minutes' => $dureeMinutes,
            'type_exam' => $type,
            'coderessource' => $resourceCode,
            'codeenseignant' => $teacherCode
        ]);

        if (!$dsId) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du DS');
        }

        $absents = $this->request->getPost('absent') ?? [];
        $justifies = $this->request->getPost('justifie') ?? [];

        foreach ($absents as $studentCode => $value) {
            $isJustified = isset($justifies[$studentCode]) ? 1 : 0;
            $this->absentModel->markAbsent($dsId, $studentCode, $isJustified);
        }

        // Définir l'état selon le nombre d'absents
        $etatInitial = (count($absents) === 0) ? 'REFUSE' : 'EN ATTENTE';
        $this->dsModel->setEtat($dsId, $etatInitial);

        return redirect()->to('ds/detail/' . $dsId)->with('success', 'DS ajouté avec succès');
    }


    /**
     * Formulaire de modification d'un DS
     */
    public function modifier($id)
    {
        $session = session();
        if (!$session->get('connected')) {
            return redirect()->to('/login');
        }

        // Vérifier si l'utilisateur est directeur des études
        $role = $session->get('fonction');
        if ($role !== 'DE') {
            return redirect()->to('DS/detail/' . $id)->with('error', 'Accès non autorisé');
        }

        $ds = $this->dsModel->getDsWithDetails($id);

        if (!$ds) {
            return redirect()->to('DS')->with('error', 'DS non trouvé');
        }

        $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);
        $keyword = $this->request->getGet('keyword') ?? '';

        // Récupérer tous les étudiants du semestre avec leur statut d'absence pour ce DS
        $data['students'] = $this->studentModel->getStudentsWithAbsenceForDs($id, $perPage, $keyword);
        $data['pager'] = $this->studentModel->pager;
        $data['keyword'] = $keyword;
        $data['ds'] = $ds;
        $data['types'] = ['MACHINE' => 'Machine', 'ORAL' => 'Oral', 'PAPIER' => 'Papier'];
        $data['validation'] = \Config\Services::validation();

        return view('DS/modifier', $data);
    }

    /**
     * Mise à jour d'un DS
     */
    public function update($id)
    {
        $session = session();
        if (!$session->get('connected')) {
            return redirect()->to('/login');
        }

        // Vérifier si l'utilisateur est directeur des études
        $role = $session->get('fonction');
        if ($role !== 'DE') {
            return redirect()->to('DS/detail/' . $id)->with('error', 'Accès non autorisé');
        }

        $ds = $this->dsModel->find($id);

        if (!$ds) {
            return redirect()->to('DS')->with('error', 'DS non trouvé');
        }

        $rules = [
            'date' => 'required|valid_date',
            'type' => 'required|in_list[MACHINE,PAPIER,ORAL]',
            'duration' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]'
        ];

        if (!$this->validate($rules)) {
            $dsDetails = $this->dsModel->getDsWithDetails($id);
            $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);
            $keyword = $this->request->getGet('keyword') ?? '';

            $data['students'] = $this->studentModel->getStudentsWithAbsenceForDs($id, $perPage, $keyword);
            $data['pager'] = $this->studentModel->pager;
            $data['keyword'] = $keyword;
            $data['ds'] = $dsDetails;
            $data['types'] = ['MACHINE' => 'Machine', 'ORAL' => 'Oral', 'PAPIER' => 'Papier'];
            $data['validation'] = $this->validator;

            return view('ds/modifier', $data);
        }

        $post = $this->request->getPost();

        $duration = $post['duration'];
        list($heures, $minutes) = explode(':', $duration);
        $dureeMinutes = ($heures * 60) + $minutes;

        $dsData = [
            'date_ds' => $post['date'],
            'duree_minutes' => $dureeMinutes,
            'type_exam' => $post['type']
        ];

        $success = $this->dsModel->updateDs($id, $dsData);

        if (!$success) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la modification du DS');
        }

        // Mettre à jour les absences
        $absents = $this->request->getPost('absent') ?? [];
        $justifies = $this->request->getPost('justifie') ?? [];

        // Supprimer toutes les absences existantes pour ce DS
        $this->absentModel->where('id_ds', $id)->delete();

        // Réinsérer les nouvelles absences
        foreach ($absents as $studentCode => $value) {
            $isJustified = isset($justifies[$studentCode]) ? 1 : 0;
            $this->absentModel->markAbsent($id, $studentCode, $isJustified);
        }

        // Mettre à jour l'état du DS
        $etatInitial = (count($absents) === 0) ? 'REFUSE' : 'EN ATTENTE';
        $this->dsModel->setEtat($id, $etatInitial);

        return redirect()->to('DS/detail/' . $id)->with('success', 'DS modifié avec succès');
    }

    /**
     * Valider un rattrapage pour un DS
     */
    public function validerRattrapage($id)
    {
        // TODO: Implémenter la validation de rattrapage
        return redirect()->to('ds/detail/' . $id)->with('success', 'Rattrapage validé');
    }

    /**
     * Refuser un rattrapage pour un DS
     */
    public function refuserRattrapage($id)
    {
        if (!$id) {
            return redirect()->to('ds')->with('error', 'ID du DS non spécifié');
        }
    
        // Vérifier que le DS existe
        $ds = $this->dsModel->find($id);
        if (!$ds) {
            return redirect()->to('ds')->with('error', 'DS non trouvé');
        }
    
        // Mettre à jour l'état à REFUSE
        $result = $this->dsModel->setEtat($id, 'REFUSE');
    
        if ($result) {
            return redirect()->to('ds')->with('success', 'Rattrapage refusé avec succès. État du DS mis à jour.');
        } else {
            return redirect()->to('ds')->with('error', 'Erreur lors du refus du rattrapage.');
        }
    }


    /**
     * API: Récupérer les ressources par semestre
     */
    public function getResourcesBySemester()
    {
        $semester = $this->request->getGet('semester');
        $resources = $this->resourceModel->getResourcesBySemesterForAjax($semester);
        return $this->response->setJSON($resources);
    }

    /**
     * API: Récupérer les professeurs par ressource
     */
    public function getTeachersByResource()
    {
        $resource = $this->request->getGet('resource');
        $teachers = $this->teacherModel->getTeachersByResourceForAjax($resource);
        return $this->response->setJSON($teachers);
    }

    /**
     * API: Récupérer les étudiants par semestre
     */
    public function getStudentsBySemester()
    {
        $semester = $this->request->getGet('semester');
        $keyword = $this->request->getGet('keyword') ?? '';
        $students = $this->studentModel->getStudentsBySemesterForAjax($semester, $keyword);
        return $this->response->setJSON($students);
    }
}
