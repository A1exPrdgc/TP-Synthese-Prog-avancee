<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TeachersModel;

class Profil extends BaseController
{
    public function __construct()
    {
        helper(['form']);
    }

    public function index()
    {
        $session = session();

        if (! $session->get('connected')) {
            return redirect()->to(site_url('login'));
        }

        $code  = $session->get('code');
        $model = new TeachersModel();

        $user = $model->find($code);

        $data = [
            'user'       => $user,
            'validation' => session()->getFlashdata('validation'),
            'error'      => session()->getFlashdata('error'),
            'message'    => session()->getFlashdata('message'),
        ];

        return view('profil/profil', $data);
    }

    public function edit()
    {
        $session = session();

        if (! $session->get('connected')) {
            return redirect()->to(site_url('login'));
        }

        $code  = $session->get('code');
        $model = new TeachersModel();

        $user = $model->find($code);

        $data = [
            'user'       => $user,
            'validation' => session()->getFlashdata('validation'),
            'error'      => session()->getFlashdata('error'),
            'message'    => session()->getFlashdata('message'),
        ];
        
        return view('profil/modification', $data);
    }
    
    public function update()
    {
        $session = session();

        if (! $session->get('connected')) {
            return redirect()->to(site_url('login'));
        }

        $code  = $session->get('code');
        $model = new TeachersModel();
        $user  = $model->find($code);
        
        $rules = [
            'nom'   => 'required|max_length[100]',
            'prenom'=> 'required|max_length[100]',
            'email' => "required|valid_email|is_unique[enseignant.email,code,{$code}]|max_length[255]",
        ];
        
        $file = $this->request->getFile('photo');
        $hasPhoto = $file && $file->isValid() && ! $file->hasMoved();
        
        if ($hasPhoto) {
            $rules['photo'] = 'uploaded[photo]'
                . '|max_size[photo,2048]'
                . '|is_image[photo]'
                . '|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]';
        }

        if (! $this->validate($rules)) {
            return redirect()
                ->to(site_url('profil/edit'))
                ->with('validation', $this->validator)
                ->withInput();
        }

        $updateData = [
            'nom'    => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'email'  => $this->request->getPost('email'),
        ];

        if ($hasPhoto) {
            
            $uploadPath = FCPATH . 'assets/images/profil'; 

            if (! is_dir($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }

            $newName = $code . '.' . $file->getExtension();
            
            $file->move($uploadPath, $newName, true);
            
            $relativePath = 'assets/images/profil/' . $newName;


            $updateData['photo'] = $relativePath;
            
            $session->set('photo', $relativePath);
        }
        
        $model->update($code, $updateData);

        $session->setFlashdata('message', 'Votre profil a été mis à jour avec succès.');

        return redirect()->to(site_url('profil'));
    }
}