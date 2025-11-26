<?php

namespace App\Controllers;

use App\Models\RattrapageModel;
use App\Models\TeachersModel;
use App\Models\StudentsModel;
use App\Models\SemestersModel;
use App\Models\RessourceModel;


class Rattrapage extends BaseController
{

    public function __construct()
    {
        $teacherModel = new TeachersModel();

        helper(['form']);
        session()->set('role', $teacherModel->getRole());
    }

    public function ajout()
    {
        //TODO VALEUR EN DUR A LIER AVEC LES DONNEES DANS VISIONNER DS 
        $students = [
            ['id' => 'E001', 'last_name' => 'Dupont', 'first_name' => 'Jean', 'class' => 'S1A'],
            ['id' => 'E002', 'last_name' => 'Martin', 'first_name' => 'Claire', 'class' => 'S1B'],
            ['id' => 'E003', 'last_name' => 'Bernard', 'first_name' => 'Luc', 'class' => 'S1A'],
            ['id' => 'E004', 'last_name' => 'Durand', 'first_name' => 'Sophie', 'class' => 'S1C'],
            ['id' => 'E005', 'last_name' => 'Leroy', 'first_name' => 'Paul', 'class' => 'S1B'],
        ];

        $data = ['semester' => 'S1', 'resource' => 'R1.05 blabla', 'teacher' => 'Legrix', 'date' => '2024-06-15', 'type' => 'Machine', 'duration' => '02:00', 'justify' => false, 'students' => $students];

        $session = session();

        if (!$session->get('user_id')) {
            return redirect()->to('/login');
        }

        $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);

        $keyword = $this->request->getGet('keyword') ?? '';

        $data['keyword'] = $keyword;

        $studentModel = new StudentsModel();
        $data['students'] = $studentModel->getPaginatedStudents($perPage, $keyword);

        $data['types'] = ['MACHINE', 'ORAL', 'PAPIER'];

        return view('ds/ajout', $data);
    }

    public function save()
    {
        $isValid = $this->validate([
            'semester' => 'required|in_list[S1,S2]',
            'resource' => 'required|in_list[R1.05 blabla,R1.02 blibli]',
            'teacher' => 'required|in_list[Legrix,Thorel]',
            'date' => 'required|valid_date',
            'hour' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]',
            'type' => 'required|in_list[Machine,Papier]',
            'duration' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]',
            'room' => 'required|alpha_numeric_space|max_length[3]|min_length[3]',
            'absent' => 'required|boolean',
            'justify' => 'required|boolean'
        ]);
        if (!$isValid) {
            return view('ds/ajout', [
                'validation' => \Config\Services::validation()
            ]);
        } else {
            $request = \Config\Services::request();
            $informations = $request->getPost();
            session()->setFlashdata('informations', $informations);
            return view('ds/success', ['informations' => $informations]);
        }
    }
}
