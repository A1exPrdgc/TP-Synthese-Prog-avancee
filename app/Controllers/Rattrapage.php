<?php

namespace App\Controllers;

use App\Controllers\BaseController;
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
        $data['DSInformation'] = ['codeEnseignant' => 'lb000005','idDs' => $idDs, 'semester' => 'S1', 'resource' => 'R1.05 blabla', 'teacher' => 'Legrix', 'date' => '2024-06-15', 'type' => 'MACHINE', 'duration' => 120, 'justify' => false];

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
        $isValid = $this->validate([
            'semester' => 'required|in_list[S1]',
            'resource' => 'required|in_list[R1.05 blabla,R1.02 blibli]',
            'teacher' => 'required|in_list[Legrix,Thorel]',
            'date' => 'required|regex_match[/^(0[1-9]|[12]\d|3[01])\/(0[1-9]|1[012])\/\d{2}$/]',
            'hour' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]',
            'type' => 'required|in_list[MACHINE,PAPIER,ORAL]',
            'duration' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]',
            'room' => 'required|alpha_numeric_space|max_length[3]|min_length[3]',
            'justify' => 'if_exist|in_list[on,off]'
        ]);
        if (!$isValid) {
            $request = \Config\Services::request();
            $informations = $request->getPost();
            $studentModel = new StudentsModel();
            $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);
            $keyword = $this->request->getGet('keyword') ?? '';
            $data = [
                'validation' => \Config\Services::validation(),
                'DSInformation' => array_merge($informations, ['idDs' => $idDs]),
                'types' => ['MACHINE' => 'MACHINE', 'ORAL' => 'ORAL', 'PAPIER' => 'PAPIER'],
                'students' => $studentModel->getPaginatedStudentsByDSiD($idDs, $perPage, $keyword),
                'keyword' => $keyword
            ];
            return view('rattrapage/ajout', $data);
        } else {
            $request = \Config\Services::request();
            $informations = $request->getPost();
            //TODO faire l'envoie de mail à l'étudiant
            $data = [
                'id_ds' => $informations['idDs'],
                'code' => $informations['codeEnseignant'],
                'date_rattrapage' => $informations['date'],
                'duree_minutes' => $informations['duration'],
                'heure_debut' => $informations['hour'],
                'etat' => 'EN ATTENTE',
                'mail_envoye' => false,
                'type_exam' => $informations['type'],
                'salle' => $informations['room']
            ];
            var_dump($data);
            $rattrapageModel = new RattrapageModel();
            $rattrapageModel->insertRattrapage($data);
            session()->setFlashdata('informations', $informations);
            return view('rattrapage/success', ['informations' => $informations]);
        }
    }
}
