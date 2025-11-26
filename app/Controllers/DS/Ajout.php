<?php
namespace App\Controllers\DS;
use CodeIgniter\Controller;
class Ajout extends Controller
{

    public function __construct()
    {
        helper(['form']);
    }

    public function index()
    {
        return view('ds/ajout');
    }

    public function save()
    {
        $isValid = $this->validate([
            'semester' => 'required|in_list[S1,S2]',
            'resource' => 'required|in_list[R1.05 blabla,R1.02 blibli]',
            'teacher' => 'required|in_list[Legrix,Thorel]',
            'date' => 'required|valid_date',
            'type' => 'required|in_list[Machine,Papier]',
            'duration' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]'     
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