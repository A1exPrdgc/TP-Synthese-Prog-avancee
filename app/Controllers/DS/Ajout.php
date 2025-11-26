<?php
namespace App\Controllers\DS;
use CodeIgniter\Controller;
use App\Models\ArticleRechercheModel;
use App\Models\StudentsModel;
use App\Models\SemestersModel;
use App\Models\RessourceModel;
use App\Models\TeachersModel;
class Ajout extends Controller
{

    public function __construct()
    {
        helper(['form']);
    }

    public function index()
    {
        $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);

        $keyword = $this->request->getGet('keyword') ?? '';

        $data['keyword'] = $keyword;

        $studentModel = new StudentsModel();
        $data['students'] = $studentModel->getPaginatedStudents( $perPage, $keyword);

        $semesterModel = new SemestersModel();
        $data['semesters'] = $semesterModel->getAllSemesters();

        $selectedSemester = $data['semesters'][0];

        $resourceModel = new RessourceModel();
        $data['resources'] = $resourceModel->getAllResourcesBySemesters($selectedSemester);

        $selectedRessource = $data['resources'][0];

        $teacherModel = new TeachersModel();
        $data['teachers'] = $teacherModel->getAllTeachersByResources($selectedRessource);

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
            'type' => 'required|in_list[MACHINE, ORAL, PAPIER]',
            'duration' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]',
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