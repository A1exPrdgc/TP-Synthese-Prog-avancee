<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DsModel;
use App\Models\TeachersModel;
use App\Models\StudentsModel;
use App\Models\RattrapageModel;
use App\Models\SemestersModel;
use App\Models\RessourceModel;

class Rattrapage extends BaseController
{
    protected $rattrapageModel;
    protected $dsModel;
    protected $teacherModel;
    protected $studentModel;
    protected $semesterModel;
    protected $resourceModel;

    public function __construct()
    {
        helper(['form']);
        
        $this->rattrapageModel = new RattrapageModel();
        $this->dsModel = new DsModel();
        $this->teacherModel = new TeachersModel();
        $this->studentModel = new StudentsModel();
        $this->semesterModel = new SemestersModel();
        $this->resourceModel = new RessourceModel();
        
        session()->set('role', $this->teacherModel->getRole());
    }

    /**
     * Liste des rattrapages avec filtres
     */
    public function index()
    {
        $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);
        
        $filters = [
            'keyword' => $this->request->getGet('keyword') ?? '',
            'resource' => $this->request->getGet('resource') ?? '',
            'semester' => $this->request->getGet('semester') ?? '',
            'teacher' => $this->request->getGet('teacher') ?? '',
            'date_debut' => $this->request->getGet('date_debut') ?? '',
            'date_fin' => $this->request->getGet('date_fin') ?? ''
        ];

        $data['rattrapagesList'] = $this->rattrapageModel->getPaginatedRattrapages($perPage, $filters);
        $data['pager'] = $this->rattrapageModel->pager;
        $data['filters'] = $filters;

        $data['semesters'] = $this->semesterModel->getAllSemestersForDropdown();
        $data['resources'] = $this->resourceModel->getAllResourcesForDropdown();
        $data['teachers'] = $this->teacherModel->getAllTeachersForDropdown();

        return view('rattrapage/index', $data);
    }

    /**
     * Formulaire d'ajout d'un rattrapage (depuis un DS)
     */
    public function ajout($idDs)
    {
        $session = session();
        if (!$session->get('connected')) {
            return redirect()->to('/login');
        }

        $dataArray = $this->dsModel->getDsWithDetails($idDs);
        
        if (!$dataArray) {
            return redirect()->to('DS')->with('error', 'DS non trouvé');
        }

        $data['DSInformation'] = [
            'idDs' => $dataArray['id_ds'],
            'codeEnseignant' => $dataArray['enseignant_code'], 
            'semester' => $dataArray['semestre_code'], 
            'resource' => $dataArray['coderessource'] . ' ' . $dataArray['nomressource'], 
            'teacher' => $dataArray['enseignant_complet'], 
            'date' => $dataArray['date_ds'], 
            'type' => $dataArray['type_exam'], 
            'duration' => $dataArray['duree_minutes']
        ];

        $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);
        $keyword = $this->request->getGet('keyword') ?? '';

        $data['keyword'] = $keyword;
        $data['students'] = $this->studentModel->getPaginatedStudentsByDSiD($idDs, $perPage, $keyword);
        $data['pager'] = $this->studentModel->pager;
        $data['types'] = ['MACHINE' => 'Machine', 'ORAL' => 'Oral', 'PAPIER' => 'Papier'];

        return view('rattrapage/ajout', $data);
    }

    /**
     * Sauvegarde d'un nouveau rattrapage
     */
    public function save($idDs)
    {
        $session = session();
        if (!$session->get('connected')) {
            return redirect()->to('/login');
        }

        $rules = [
            'date' => 'required|valid_date',
            'hour' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]',
            'type' => 'required|in_list[MACHINE,PAPIER,ORAL]',
            'duration' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]',
            'room' => 'required|alpha_numeric|max_length[3]|min_length[3]'
        ];

        if (!$this->validate($rules)) {
            // Recharger les données pour la vue
            $dataArray = $this->dsModel->getDsWithDetails($idDs);
            $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);
            $keyword = $this->request->getGet('keyword') ?? '';

            $data['DSInformation'] = [
                'idDs' => $dataArray['id_ds'],
                'codeEnseignant' => $dataArray['enseignant_code'], 
                'semester' => $dataArray['semestre_code'], 
                'resource' => $dataArray['coderessource'] . ' ' . $dataArray['nomressource'], 
                'teacher' => $dataArray['enseignant_complet'], 
                'date' => $dataArray['date_ds'], 
                'type' => $dataArray['type_exam'], 
                'duration' => $dataArray['duree_minutes']
            ];

            $data['keyword'] = $keyword;
            $data['students'] = $this->studentModel->getPaginatedStudentsByDSiD($idDs, $perPage, $keyword);
            $data['pager'] = $this->studentModel->pager;
            $data['types'] = ['MACHINE' => 'Machine', 'ORAL' => 'Oral', 'PAPIER' => 'Papier'];
            $data['validation'] = $this->validator;
            $data['old_justify'] = $this->request->getPost('justify') ?? [];

            return view('rattrapage/ajout', $data);
        }

        // Récupérer les données du formulaire
        $post = $this->request->getPost();
        
        // Convertir la durée en minutes
        $duration = $post['duration'];
        list($heures, $minutes) = explode(':', $duration);
        $dureeMinutes = ($heures * 60) + $minutes;

        $rattrapageData = [
            'id_ds' => (int) $idDs,
            'code' => $post['codeEnseignant'],
            'date_rattrapage' => $post['date'],
            'duree_minutes' => $dureeMinutes,
            'heure_debut' => $post['hour'],
            'etat' => 'EN ATTENTE',
            'mail_envoye' => 0,
            'salle' => $post['room']
        ];

        $rattrapageModel = new RattrapageModel();
        $rattrapageModel->insertRattrapage($data);

        $dsModel = new DsModel();
        $dsModel->setEtat($idDs, 'PREVU');
    
        return view('rattrapage');
        $rattrapageId = $this->rattrapageModel->insertRattrapage($rattrapageData);

        if (!$rattrapageId) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du rattrapage');
        }

        return redirect()->to('Rattrapage')->with('success', 'Rattrapage ajouté avec succès');
    }

    /**
     * Détail d'un rattrapage
     */
    public function detail($id)
    {
        $rattrapage = $this->rattrapageModel->getRattrapageWithDetails($id);
        
        if (!$rattrapage) {
            return redirect()->to('Rattrapage')->with('error', 'Rattrapage non trouvé');
        }

        $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);
        $keyword = $this->request->getGet('keyword') ?? '';

        // Récupérer les étudiants absents du DS associé
        $data['students'] = $this->studentModel->getPaginatedStudentsByDSiD($rattrapage['id_ds'], $perPage, $keyword);
        $data['pager'] = $this->studentModel->pager;
        $data['keyword'] = $keyword;
        $data['rattrapage'] = $rattrapage;

        return view('rattrapage/detail', $data);
    }

    /**
     * Formulaire de modification d'un rattrapage
     */
    public function modifier($id)
    {
        $session = session();
        if (!$session->get('connected')) {
            return redirect()->to('/login');
        }

        $rattrapage = $this->rattrapageModel->getRattrapageWithDetails($id);
        
        if (!$rattrapage) {
            return redirect()->to('Rattrapage')->with('error', 'Rattrapage non trouvé');
        }

        $data['rattrapage'] = $rattrapage;
        $data['types'] = ['MACHINE' => 'Machine', 'ORAL' => 'Oral', 'PAPIER' => 'Papier'];
        $data['validation'] = \Config\Services::validation();

        return view('rattrapage/modifier', $data);
    }

    /**
     * Mise à jour d'un rattrapage
     */
    public function update($id)
    {
        $session = session();
        if (!$session->get('connected')) {
            return redirect()->to('/login');
        }

        $rattrapage = $this->rattrapageModel->find($id);
        
        if (!$rattrapage) {
            return redirect()->to('Rattrapage')->with('error', 'Rattrapage non trouvé');
        }

        $rules = [
            'date' => 'required|valid_date',
            'hour' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]',
            'type' => 'required|in_list[MACHINE,PAPIER,ORAL]',
            'duration' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]',
            'room' => 'required|alpha_numeric|max_length[3]|min_length[3]'
        ];

        if (!$this->validate($rules)) {
            $rattrapageDetails = $this->rattrapageModel->getRattrapageWithDetails($id);
            $data['rattrapage'] = $rattrapageDetails;
            $data['types'] = ['MACHINE' => 'Machine', 'ORAL' => 'Oral', 'PAPIER' => 'Papier'];
            $data['validation'] = $this->validator;

            return view('rattrapage/modifier', $data);
        }

        // Récupérer les données du formulaire
        $post = $this->request->getPost();
        
        // Convertir la durée en minutes
        $duration = $post['duration'];
        list($heures, $minutes) = explode(':', $duration);
        $dureeMinutes = ($heures * 60) + $minutes;

        $rattrapageData = [
            'date_rattrapage' => $post['date'],
            'duree_minutes' => $dureeMinutes,
            'heure_debut' => $post['hour'],
            'salle' => $post['room']
        ];

        $success = $this->rattrapageModel->updateRattrapage($id, $rattrapageData);

        if (!$success) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la modification du rattrapage');
        }

        return redirect()->to('Rattrapage/detail/' . $id)->with('success', 'Rattrapage modifié avec succès');
    }
}
