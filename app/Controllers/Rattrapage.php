<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DsModel;
use App\Models\TeachersModel;
use App\Models\StudentsModel;
use App\Models\RattrapageModel;


class Rattrapage extends BaseController
{

    public function __construct()
    {
        $teacherModel = new TeachersModel();
        

        helper(['form']);
        session()->set('role', $teacherModel->getRole());
    }

    public function ajout($idDs)
    {
        $dsModel = new DsModel();
        $dataArray = $dsModel->getDsWithDetails($idDs);
        $data['DSInformation'] = [
            'idDs' => $dataArray["id_ds"],
            'codeEnseignant' => $dataArray["enseignant_code"], 
            'semester' => $dataArray["semestre_code"], 
            'resource' => $dataArray["nomressource"], 
            'teacher' => $dataArray["enseignant_nom"], 
            'date' => $dataArray["date_ds"], 
            'type' => $dataArray["type_exam"], 
            'duration' => $dataArray["duree_minutes"], 
            'justify' => false
        ];

        $session = session();

        if (!$session->get('connected')) {
            return redirect()->to('/login');
        }

        $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);

        $keyword = $this->request->getGet('keyword') ?? '';

        $data['keyword'] = $keyword;

        $studentModel = new StudentsModel();

        $data['students'] = $studentModel->getPaginatedStudentsByDSiD($idDs, $perPage, $keyword);

        $data['types'] = ['MACHINE' => 'MACHINE', 'ORAL' => 'ORAL', 'PAPIER' => 'PAPIER'];

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

        $rules = [
            'semester' => 'required|in_list[S1]',
            'resource' => 'required|in_list[R1.05 blabla,R1.02 blibli]',
            'teacher' => 'required|in_list[Legrix,Thorel]',
            'date' => 'required|regex_match[/^(0[1-9]|[12]\d|3[01])\/(0[1-9]|1[012])\/\d{2}$/]',
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

        var_dump($idDs);

        var_dump($informations['codeEnseignant']);

        var_dump($informations['duration']);

        var_dump($informations['hour']);

        var_dump($informations['type']);

        var_dump($informations['room']);


        $duration = substr($informations['duration'], 0, 2) * 60 + substr($informations['duration'], 3, 2);

        var_dump($duration);

        $data = [
            'id_ds' => (int) $idDs,
            'code' => $informations['codeEnseignant'],
            'date_rattrapage' => $informations['date'],
            'duree_minutes' => $duration,
            'heure_debut' => $informations['hour'],
            'etat' => 'EN ATTENTE',
            'mail_envoye' => 0,
            'type_exam' => $informations['type'],
            'salle' => $informations['room']
        ];

        $rattrapageModel = new RattrapageModel();
        $rattrapageModel->insertRattrapage($data);

        $dsModel = new DsModel();
        $dsModel->setEtat($idDs, 'PREVU');
    
        return view('rattrapage');
    }
}
