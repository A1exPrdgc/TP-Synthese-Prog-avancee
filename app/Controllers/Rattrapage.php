<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AbsentModel;
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
    protected $absenceModel;
    protected $emailControl;

    public function __construct()
    {
        helper(['form']);
        
        $this->rattrapageModel = new RattrapageModel();
        $this->dsModel = new DsModel();
        $this->teacherModel = new TeachersModel();
        $this->studentModel = new StudentsModel();
        $this->semesterModel = new SemestersModel();
        $this->resourceModel = new RessourceModel();
        $this->absenceModel = new AbsentModel();
        $this->emailControl = new MailController();
        
        session()->set('role', $this->teacherModel->getRole());
    }

    /**
     * Liste des rattrapages avec filtres
     */
    public function index()
    {

        $this->rattrapageModel->updateEtatByDate();

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
            return redirect()->to('/connecter');
        }

        $dataArray = $this->dsModel->getDsWithDetails($idDs);
        
        if (!$dataArray) {
            return redirect()->to('ds')->with('error', 'DS non trouvé');
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

    public function save($idDs)
    {
        $request = \Config\Services::request();
        $post = $request->getPost();

        $studentModel = new StudentsModel();
        $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);
        $keyword = $this->request->getGet('keyword') ?? '';
        $students = $studentModel->getPaginatedStudentsByDSiD($idDs, $perPage, $keyword);

        $justifies = $post['justify'] ?? [];
        foreach ($students as $s) {
            $sid = $s['id'];
            if (!isset($justifies[$sid])) {
                $justifies[$sid] = 'off';
            }
        }
        $post['justify'] = $justifies;

        $dsInformation = $this->dsModel->getDsWithDetails($idDs);

        $semesterCode = $dsInformation['semestre_code'];
        $resourceCode = $dsInformation['coderessource'] . ' ' . $dsInformation['nomressource'];
        $teacherName = $dsInformation['enseignant_complet'];

        $rules = [
            'semester' => 'required|in_list[' . $semesterCode . ']',
            'resource' => 'required|in_list[' . $resourceCode . ']',
            'teacher' => 'required|in_list[' . $teacherName . ']',
            'date' => 'required|valid_date',
            'hour' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]',
            'type' => 'required|in_list[MACHINE,PAPIER,ORAL]',
            'duration' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]',
            'room' => 'required|alpha_numeric_space|max_length[3]|min_length[3]'
        ];

        $validation = \Config\Services::validation();
        $validation->setRules($rules);
        $isValid = $validation->run($post);

        foreach ($post['justify'] as $val) {
            if (!in_array($val, ['on', 'off'], true)) {
                $validation->setError('justify', 'Valeur invalide pour justify');
                $isValid = false;
                break;
            }
        }

        if (!$isValid) {
            $data = [
                'validation' => $validation,
                'DSInformation' => array_merge($post, ['idDs' => $idDs]),
                'types' => ['MACHINE' => 'MACHINE', 'ORAL' => 'ORAL', 'PAPIER' => 'PAPIER'],
                'students' => $students,
                'keyword' => $keyword,
                'old_justify' => $post['justify'] ?? []
            ];

            return view('rattrapage/ajout', $data);
        }

        $informations = $post;

        $duration = substr($informations['duration'], 0, 2) * 60 + substr($informations['duration'], 3, 2);

        $data = [
            'id_ds' => (int) $idDs,
            'code' => $informations['codeEnseignant'],
            'date_rattrapage' => $informations['date'],
            'duree_minutes' => $duration,
            'heure_debut' => $informations['hour'],
            'etat' => 'PREVU',
            'mail_envoye' => 0,
            'type_exam' => $informations['type'],
            'salle' => $informations['room']
        ];

        foreach ($students as $student) {
            $codeEtudiant = $student['id'];
            $justified = ($post['justify'][$codeEtudiant] === 'on') ? 1 : 0;
            $this->absenceModel->markForMakeup((int) $idDs, $codeEtudiant, $justified);
            if ($justified === 1){
                 $message = "<p>Bonjour " . $student['prenom'] . " " . $student['nom'] . ",</p>"
                        . "<p>Un rattrapage a été programmé pour le DS initialement prévu le " . $dsInformation['date_ds'] . ".</p>"
                        . "<p><b>Détails du rattrapage :</b><br>"
                        . "Date : " . $informations['date'] . "<br>"
                        . "Heure : " . $informations['hour'] . "<br>"
                        . "Durée : " . $informations['duration'] . "<br>"
                        . "Salle : " . $informations['room'] . "<br>"
                        . "Type : " . $informations['type'] . "</p>"
                        . "<p>Veuillez vous présenter à l'heure indiquée.</p>"
                        . "<p>Cordialement,<br>L'équipe MySGRDS</p>";
                $this->emailControl->sendMail($student['email'], 'Nouveau Rattrapage le ' . $informations['date'], $message );
            }
        }

        $rattrapageModel = new RattrapageModel();

        $dsModel = new DsModel();
        $dsModel->setEtat($idDs, 'PREVU');

        $rattrapageId = $rattrapageModel->insertRattrapage($data);

        if (!$rattrapageId) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du rattrapage');
        }

        return redirect()->to('rattrapage')->with('success', 'Rattrapage ajouté avec succès');
    
    }

    //A appler quand on annule un rattrapage
    public function refuser($id)
    {
        if (!$id) {
            return redirect()->to('rattrapage')->with('error', 'ID du rattrapage non spécifié');
        }

        $rattrapage = $this->rattrapageModel->find($id);
        if (!$rattrapage) {
            return redirect()->to('rattrapage')->with('error', 'Rattrapage non trouvé');
        }

        $this->rattrapageModel->updateEtat($id, 'REFUSE');
        $rattrapage = $this->rattrapageModel->getRattrapageWithDetails($id);

        $students = $this->studentModel->getPaginatedStudentsByDSiD($rattrapage['id_ds'], 1000, null);

        foreach ($students as $student) {
            $this->absenceModel->markForMakeup($rattrapage['id_ds'], $student['id'], 0);
            $this->emailControl->sendMail($student['email'], 'Rattrapage ANNULÉ pour le DS du ' . $rattrapage['date_ds'], 
                "<p>Bonjour " . $student['prenom'] . " " . $student['nom'] . ",</p>"
                . "<p>Le rattrapage initialement prévu le " . $rattrapage['date_rattrapage'] . " pour le DS du " . $rattrapage['date_ds'] . " a été annulé.</p>"
                . "<p>Cordialement,<br>L'équipe MySGRDS</p>"
            );
        }

        return redirect()->to('rattrapage')->with('success', 'Rattrapage annulé avec succès.');
    }
    /**
     * Détail d'un rattrapage
     */
    public function detail($id)
    {
        $rattrapage = $this->rattrapageModel->getRattrapageWithDetails($id);
        
        if (!$rattrapage) {
            return redirect()->to('rattrapage')->with('error', 'Rattrapage non trouvé');
        }

        $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);
        $keyword = $this->request->getGet('keyword') ?? '';

        // Récupérer les étudiants absents du DS associé
        $data['students'] = $this->studentModel->getPaginatedAbsentStudentsAndRattrapeByDSiD($rattrapage['id_ds'], $perPage, $keyword);
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
            return redirect()->to('/connecter');
        }

        $rattrapage = $this->rattrapageModel->getRattrapageWithDetails($id);
        
        if (!$rattrapage) {
            return redirect()->to('rattrapage')->with('error', 'Rattrapage non trouvé');
        }

        $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);
        $keyword = $this->request->getGet('keyword') ?? '';

        // Récupérer les étudiants absents du DS associé
        $data['students'] = $this->studentModel->getPaginatedStudentsByDSiD($rattrapage['id_ds'], $perPage, $keyword);
        $data['pager'] = $this->studentModel->pager;
        $data['keyword'] = $keyword;
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
            return redirect()->to('/connecter');
        }

        $rattrapage = $this->rattrapageModel->find($id);
        
        if (!$rattrapage) {
            return redirect()->to('rattrapage')->with('error', 'Rattrapage non trouvé');
        }

        $rules = [
            'date' => 'required|valid_date',
            'hour' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]',
            'type' => 'required|in_list[MACHINE,PAPIER,ORAL]',
            'duration' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]',
            'room' => 'required|alpha_numeric|max_length[3]|min_length[3]'
        ];

        $this->rattrapageModel->updateEtat($id, 'PREVU');

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
            'salle' => $post['room'],
            'type_exam' => $post['type']
        ];

        $success = $this->rattrapageModel->updateRattrapage($id, $rattrapageData);

        if (!$success) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la modification du rattrapage');
        }

        $rattrapes = $this->request->getPost('rattrape') ?? [];
        $rattrapage = $this->rattrapageModel->getRattrapageWithDetails($id);
        
        $students = $this->studentModel->getPaginatedStudentsByDSiD($rattrapage['id_ds'], 1000, null);
        
        foreach ($students as $student) {
            $codeEtudiant = $student['id'];
            $rattrape = isset($rattrapes[$codeEtudiant]) ? 1 : 0;
            $this->absenceModel->markForMakeup($rattrapage['id_ds'], $codeEtudiant, $rattrape);
            $this->emailControl->sendMail($student['email'], 'Modification Rattrapage le ' . $post['date'], 
                "<p>Bonjour " . $student['prenom'] . " " . $student['nom'] . ",</p>"
                . "<p>Le rattrapage initialement prévu a été modifié. Voici les nouveaux détails :</p>"
                . "<p><b>Détails du rattrapage :</b><br>"
                . "Ressource : " . $rattrapage['coderessource'] . ' ' . $rattrapage['nomressource'] . "<br>"
                . "Date : " . $post['date'] . "<br>"
                . "Heure : " . $post['hour'] . "<br>"
                . "Durée : " . $post['duration'] . "<br>"
                . "Salle : " . $post['room'] . "<br>"
                . "Type : " . $post['type'] . "</p>"
                . "<p>Veuillez vous présenter à l'heure indiquée.</p>"
                . "<p>Cordialement,<br>L'équipe MySGRDS</p>"
            );
        }

        

        return redirect()->to('rattrapage/detail/' . $id)->with('success', 'Rattrapage modifié avec succès');
    }
}
