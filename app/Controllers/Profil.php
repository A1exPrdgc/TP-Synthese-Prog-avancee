<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TeacherModel;

class Profil extends BaseController
{
    public function index()
    {
        $session = session();

        // Sécurité : si pas connecté -> login
        if (! $session->get('connected')) {
            return redirect()->to(site_url('login'));
        }

        $code  = $session->get('code');
        $model = new EnseignantModel();

        $user = $model->find($code);

        $data = [
            'user'       => $user,
            'validation' => session()->getFlashdata('validation'),
            'error'      => session()->getFlashdata('error'),
            'message'    => session()->getFlashdata('message'),
        ];

        return view('profil/profil', $data);
    }

    public function update()
    {
        $session = session();

        if (! $session->get('connected')) {
            return redirect()->to(site_url('login'));
        }

        $code  = $session->get('code');
        $model = new EnseignantModel();
        $user  = $model->find($code);

        // Règles de validation pour l’image
        $rules = [
            'photo' => 'uploaded[photo]'
                . '|max_size[photo,2048]'
                . '|is_image[photo]'
                . '|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]'
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->to(site_url('profil'))
                ->with('validation', $this->validator)
                ->withInput();
        }

        $file = $this->request->getFile('photo');

        if ($file && $file->isValid() && ! $file->hasMoved()) {

            // Dossier public pour les photos de profil
            $uploadPath = FCPATH . 'assets/images';

            if (! is_dir($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }

            $newName = $code . '_' . time() . '.' . $file->getExtension();
            $file->move($uploadPath, $newName);

            $relativePath = 'uploads/profil/' . $newName;

            // On supprime l’ancienne photo si elle existe physiquement
            if (! empty($user['photo']) && is_file(FCPATH . $user['photo'])) {
                @unlink(FCPATH . $user['photo']);
            }

            // Mise à jour en BDD
            $model->update($code, ['photo' => $relativePath]);

            // On garde aussi l’info en session si tu veux l’afficher ailleurs
            $session->set('photo', $relativePath);

            $session->setFlashdata('message', 'Photo de profil mise à jour.');
        }

        return redirect()->to(site_url('profil'));
    }
}
