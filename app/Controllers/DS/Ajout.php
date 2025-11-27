<?php
namespace App\Controllers\DS;
use CodeIgniter\Controller;
use App\Models\StudentsModel;
use App\Models\SemestersModel;
use App\Models\RessourceModel;
use App\Models\TeachersModel;
class Ajout extends Controller
{

    public function __construct()
    {
        $teacherModel = new TeachersModel();

        helper(['form']);
        session()->set('role', $teacherModel->getRole());
    }

    public function index()
    {
        $perPage = max((int) ($this->request->getGet('perPage') ?? 10), 1);

        $keyword = $this->request->getGet('keyword') ?? '';

        $data['keyword'] = $keyword;

        $studentModel = new StudentsModel();
        $data['students'] = $studentModel->getPaginatedStudents( $perPage, $keyword);
        $data['pager'] = $studentModel->pager;

        $semesterModel = new SemestersModel();
        $semestersData = $semesterModel->getAllSemesters();
        $data['semesters'] = [];
        foreach ($semestersData as $semester) {
            $data['semesters'][$semester['code']] = $semester['code'];
        }

        $selectedSemester = array_key_first($data['semesters']);
        if (!$selectedSemester) {
             $selectedSemester = ''; 
        }

        $resourceModel = new RessourceModel();
        $resourcesData = $resourceModel->getAllResourcesBySemesters($selectedSemester);
        $data['resources'] = [];
        foreach ($resourcesData as $res) {
            $data['resources'][$res['coderessource']] = $res['nomressource'];
        }

        $selectedRessource = array_key_first($data['resources']);
        if (!$selectedRessource) {
            $selectedRessource = '';
        }

        $teacherModel = new TeachersModel();
        $teachersData = $teacherModel->getAllTeachersByResources($selectedRessource);
        $data['teachers'] = [];
        foreach ($teachersData as $teacher) {
            $data['teachers'][$teacher['nom']] = $teacher['nom'];
        }

        $data['types'] = ["Machine" => "Machine", "Papier" => "Papier", "Oral" => "Oral"];

        $data['semester'] = [$selectedSemester];
        $data['resource'] = [$selectedRessource];
        $data['teacher'] = [array_key_first($data['teachers']) ?? ''];
        $data['type'] = [array_key_first($data['types']) ?? ''];

        return view('ds/ajout', $data);
    }

    public function save()
    {
        $isValid = $this->validate([
            //TODO Faire la vérif en fonction des données en base avec une boucle / aussi dans rattrtapage
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