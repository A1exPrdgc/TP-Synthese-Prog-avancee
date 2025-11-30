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
use App\Controllers\AuthController;

class Rattrapage extends BaseController
{
    protected $rattrapageModel;
    protected $dsModel;
    protected $teacherModel;
    protected $studentModel;
    protected $semesterModel;
    protected $resourceModel;
    protected $absenceModel;
    protected $authController;
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
        $this->authController = new Auth();
        $this->emailControl = new MailController();
        
        session()->set('role', $this->teacherModel->getRole());
    }

    /**
     * Liste des rattrapages avec filtres
     */
    public function index()
    {
        if ($this->authController->isConnected()) {
            return redirect()->to('connecter')->with('error', 'Vous n\'êtes pas connecté.');
        }

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

        $rattrapagesList = $this->rattrapageModel->getPaginatedRattrapages($perPage, $filters);
        $pager = $this->rattrapageModel->pager;

        $data = [
            'rattrapagesList' => $rattrapagesList,
            'pager' => $pager,
            'filters' => $filters,
            'semesters' => $this->semesterModel->getAllSemestersForDropdown(),
            'resources' => $this->resourceModel->getAllResourcesForDropdown(),
            'teachers' => $this->teacherModel->getAllTeachersForDropdown()
        ];

        return view('rattrapage/index', $data);
    }


    /**
     * Formulaire d'ajout d'un rattrapage (depuis un DS)
     */
    public function ajout($idDs)
    {

        if ($this->authController->isConnected()) {
            return redirect()->to('connecter')->with('error', 'Vous n\'êtes pas connecté.');
        }

        if ($this->authController->isENS()) {
            return redirect()->to('rattrapage')->with('error', 'Vous n\'avez pas la permission d\'accéder à cette page.');
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
        if ($this->authController->isConnected()) {
            return redirect()->to('connecter')->with('error', 'Vous n\'êtes pas connecté.');
        }

        if ($this->authController->isENS()) {
            return redirect()->to('rattrapage')->with('error', 'Vous n\'avez pas la permission d\'accéder à cette page.');
        }

        $post = $this->request->getPost();

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

        $rules = [
            'semester' => 'required|in_list[' . $dsInformation['semestre_code'] . ']',
            'resource' => 'required|in_list[' . $dsInformation['coderessource'] . ' ' . $dsInformation['nomressource'] . ']',
            'teacher' => 'required|in_list[' . $dsInformation['enseignant_complet'] . ']',
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

        $duration = substr($post['duration'], 0, 2) * 60 + substr($post['duration'], 3, 2);

        $rattrapageData = [
            'id_ds' => (int) $idDs,
            'code' => $post['codeEnseignant'],
            'date_rattrapage' => $post['date'],
            'duree_minutes' => $duration,
            'heure_debut' => $post['hour'],
            'etat' => 'PREVU',
            'mail_envoye' => 0,
            'type_exam' => $post['type'],
            'salle' => $post['room']
        ];

        $rattrapageModel = new RattrapageModel();
        $rattrapantModel = new \App\Models\RattrapantModel();

        $rattrapageId = $rattrapageModel->insertRattrapage($rattrapageData);

        if (!$rattrapageId) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du rattrapage');
        }


        foreach ($students as $student) {
            $codeEtudiant = $student['id'];
            $justified = ($post['justify'][$codeEtudiant] === 'on') ? 1 : 0;

            if ($justified === 1 && !empty($codeEtudiant)) {
                $rattrapantModel->addRattrapant($rattrapageId, $codeEtudiant);
            }

            $this->absenceModel->markForMakeup((int) $idDs, $codeEtudiant, $justified);

            if ($justified === 1 && !empty($student['email'])) {
                $message = "<p>Bonjour " . esc($student['prenom']) . " " . esc($student['nom']) . ",</p>"
                    . "<p>Un rattrapage a été programmé pour le DS initialement prévu le " . esc($dsInformation['date_ds']) . ".</p>"
                    . "<p><b>Détails du rattrapage :</b><br>"
                    . "Date : " . esc($post['date']) . "<br>"
                    . "Heure : " . esc($post['hour']) . "<br>"
                    . "Durée : " . esc($post['duration']) . "<br>"
                    . "Salle : " . esc($post['room']) . "<br>"
                    . "Type : " . esc($post['type']) . "</p>"
                    . "<p>Veuillez vous présenter à l'heure indiquée.</p>"
                    . "<p>Cordialement,<br>L'équipe MySGRDS</p>";
                $this->emailControl->sendMail($student['email'], 'Nouveau Rattrapage le ' . esc($post['date']), $message);
            }
        }

        $dsModel = new DsModel();
        $dsModel->setEtat($idDs, 'PREVU');

        return redirect()->to('rattrapage')->with('success', 'Rattrapage ajouté avec succès');
    }



    //A appler quand on annule un rattrapage
    public function refuser($id)
    {
        if ($this->authController->isConnected()) {
            return redirect()->to('connecter')->with('error', 'Vous n\'êtes pas connecté.');
        }

        if ($this->authController->isENS()) {
            return redirect()->to('rattrapage')->with('error', 'Vous n\'avez pas la permission d\'accéder à cette page.');
        }
        if (!$id) {
            return redirect()->to('rattrapage')->with('error', 'ID du rattrapage non spécifié');
        }

        $rattrapage = $this->rattrapageModel->getRattrapageWithDetails($id);
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
        if ($this->authController->isConnected()) {
            return redirect()->to('connecter')->with('error', 'Vous n\'êtes pas connecté.');
        }

        $rattrapage = $this->rattrapageModel->getRattrapageWithDetails($id);
        if (!$rattrapage) {
            return redirect()->to('rattrapage')->with('error', 'Rattrapage non trouvé');
        }

        $rattrapantModel = new \App\Models\RattrapantModel();
        $rattrapants = $rattrapantModel->getRattrapantsByRattrapage($id);
        $studentIds = array_column($rattrapants, 'codeetudiant');

        $students = [];
        if (!empty($studentIds)) {
            $students = $this->studentModel->getStudentsWithJustifieByCodesAndDs($studentIds, (int)$rattrapage['id_ds']);
        }

        $pager = null; // ou relier à une pagination si tu en as besoin

        $data = [
            'rattrapage' => $rattrapage,
            'students' => $students,
            'pager' => $pager,
            'keyword' => $this->request->getGet('keyword') ?? ''
        ];

        return view('rattrapage/detail', $data);
    }


    /**
     * Formulaire de modification d'un rattrapage
     */
    public function modifier($id)
    {
        if ($this->authController->isConnected()) {
            return redirect()->to('connecter')->with('error', 'Vous n\'êtes pas connecté.');
        }

        if ($this->authController->isENS()) {
            return redirect()->to('rattrapage')->with('error', 'Vous n\'avez pas la permission d\'accéder à cette page.');
        }

        $rattrapage = $this->rattrapageModel->getRattrapageWithDetails($id);
        if (!$rattrapage) {
            return redirect()->to('rattrapage')->with('error', 'Rattrapage non trouvé');
        }

        $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);
        $keyword = $this->request->getGet('keyword') ?? '';

        // Tous les absents du DS
        $students = $this->studentModel->getPaginatedStudentsByDSiD($rattrapage['id_ds'], $perPage, $keyword);

        // Rattrapants déjà liés
        $rattrapantModel = new \App\Models\RattrapantModel();
        $rattrapants = $rattrapantModel->getRattrapantsByRattrapage($id);
        $rattrapantCodes = array_column($rattrapants, 'codeetudiant');

        // Mettre à jour la clé 'rattrape' pour cocher les rattrapants
        foreach ($students as &$student) {
            $student['rattrape'] = in_array($student['id'], $rattrapantCodes);
        }

        $pager = $this->studentModel->pager ?? null;

        $data = [
            'students' => $students,
            'pager' => $pager,
            'keyword' => $keyword,
            'rattrapage' => $rattrapage,
            'types' => ['MACHINE' => 'Machine', 'ORAL' => 'Oral', 'PAPIER' => 'Papier'],
            'validation' => \Config\Services::validation()
        ];

        return view('rattrapage/modifier', $data);
    }



    /**
     * Mise à jour d'un rattrapage
     */
    public function update($id)
    {
        if ($this->authController->isConnected()) {
            return redirect()->to('connecter')->with('error', 'Vous n\'êtes pas connecté.');
        }

        if ($this->authController->isENS()) {
            return redirect()->to('rattrapage')->with('error', 'Vous n\'avez pas la permission d\'accéder à cette page.');
        }

        $rattrapage = $this->rattrapageModel->getRattrapageWithDetails($id);
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

        $post = $this->request->getPost();
        list($hours, $minutes) = explode(':', $post['duration']);
        $durationMinutes = ($hours * 60) + $minutes;

        $rattrapageData = [
            'date_rattrapage' => $post['date'],
            'duree_minutes' => $durationMinutes,
            'heure_debut' => $post['hour'],
            'salle' => $post['room'],
            'type_exam' => $post['type']
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        $success = $this->rattrapageModel->updateRattrapage($id, $rattrapageData);
        if (!$success) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la modification du rattrapage');
        }

        $rattrapes = $this->request->getPost('rattrape') ?? [];
        $rattrapantModel = new \App\Models\RattrapantModel();

        $oldRattrapants = $rattrapantModel->getRattrapantsByRattrapage($id);
        $oldCodes = array_column($oldRattrapants, 'codeetudiant');

        $toAdd = array_diff(array_keys($rattrapes), $oldCodes);
        $toRemove = array_diff($oldCodes, array_keys($rattrapes));

        foreach ($toRemove as $codeEtudiant) {
            $rattrapantModel->removeRattrapant($id, $codeEtudiant);
        }

        foreach ($toAdd as $codeEtudiant) {
            $rattrapantModel->addRattrapant($id, $codeEtudiant);
        }

        $students = $this->studentModel->getPaginatedStudentsByDSiD($rattrapage['id_ds'], 1000, null);
        foreach ($students as $student) {
            $codeEtudiant = $student['id'];
            $isRattrape = isset($rattrapes[$codeEtudiant]) ? 1 : 0;
            $this->absenceModel->markForMakeup($rattrapage['id_ds'], $codeEtudiant, $isRattrape);

            if (!empty($student['email'])) {
                $this->emailControl->sendMail(
                    $student['email'],
                    'Modification Rattrapage le ' . $post['date'],
                    "<p>Bonjour " . esc($student['prenom']) . " " . esc($student['nom']) . ",</p>" .
                    "<p>Le rattrapage initialement prévu a été modifié. Voici les nouveaux détails :</p>" .
                    "<p><b>Détails du rattrapage :</b><br>" .
                    "Ressource : " . $rattrapage['coderessource'] . ' ' . $rattrapage['nomressource'] . "<br>" .
                    "Date : " . $post['date'] . "<br>" .
                    "Heure : " . $post['hour'] . "<br>" .
                    "Durée : " . $post['duration'] . "<br>" .
                    "Salle : " . $post['room'] . "<br>" .
                    "Type : " . $post['type'] . "</p>" .
                    "<p>Veuillez vous présenter à l'heure indiquée.</p>" .
                    "<p>Cordialement,<br>L'équipe MySGRDS</p>"
                );
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la transaction');
        }

        return redirect()->to('rattrapage/detail/' . $id)->with('success', 'Rattrapage modifié avec succès');
    }

}
