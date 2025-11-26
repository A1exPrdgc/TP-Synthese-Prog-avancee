<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EnseignantModel;

class Auth extends BaseController
{
    public function login()
    {
        helper(['form']);

        // GET → afficher le formulaire
        if ($this->request->getMethod() === 'get') {
            return view('auth/login', [
                'error'   => session()->getFlashdata('error'),
                'message' => session()->getFlashdata('message'),
            ]);
        }

        // POST → traiter la connexion
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return view('auth/login', [
                'error' => 'Veuillez remplir tous les champs.',
            ]);
        }

        $username = $this->request->getPost('username');   // code ou email
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        $model = new EnseignantModel();

        // login possible par code OU par email
        $user = $model
            ->groupStart()
                ->where('code', $username)
                ->orWhere('email', $username)
            ->groupEnd()
            ->first();

        if (! $user) {
            return view('auth/login', [
                'error' => 'Identifiant ou mot de passe incorrect.',
            ]);
        }

        if (! password_verify($password, $user['password'])) {
            return view('auth/login', [
                'error' => 'Identifiant ou mot de passe incorrect.',
            ]);
        }

        // OK → on enregistre la session
        $session = session();
        $session->set([
            'connected'   => true,
            'code'        => $user['code'],
            'nom'         => $user['nom'],
            'prenom'      => $user['prenom'],
            'email'       => $user['email'],
            'fonction'    => $user['fonction'],   // ENS / DE
        ]);

        // "Se souvenir de moi" → cookie simple sur le code
        if ($remember) {
            helper('cookie');
            // expire dans 30 jours
            set_cookie('remember_code', $user['code'], 60 * 60 * 24 * 30);
        }

        // redirection vers la page rattrapage par défaut
        return redirect()->to(site_url('rattrapage'));
    }

    public function signin()
    {
        helper(['form']);

        // GET → afficher le formulaire
        if ($this->request->getMethod() === 'get') {
            return view('auth/signin', [
                'error'   => session()->getFlashdata('error'),
                'message' => session()->getFlashdata('message'),
            ]);
        }

        // POST → créer le compte enseignant
        $rules = [
            'nom'              => 'required',
            'prenom'           => 'required',
            'email'            => 'required|valid_email',
            'username'         => 'required|alpha_numeric|max_length[10]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return view('auth/signin', [
                'error' => 'Formulaire invalide : vérifiez les champs.',
            ]);
        }

        $code     = $this->request->getPost('username');
        $nom      = $this->request->getPost('nom');
        $prenom   = $this->request->getPost('prenom');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $model = new EnseignantModel();

        // vérifier unicité code + email
        $exists = $model
            ->groupStart()
                ->where('code', $code)
                ->orWhere('email', $email)
            ->groupEnd()
            ->first();

        if ($exists) {
            return view('auth/signin', [
                'error' => 'Ce code ou cet email est déjà utilisé.',
            ]);
        }

        $data = [
            'code'     => $code,
            'nom'      => $nom,
            'prenom'   => $prenom,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            // par défaut tous ENS, tu pourras forcer DE à la main
            'fonction' => 'ENS',
        ];

        $model->insert($data);

        session()->setFlashdata('message', 'Compte créé, vous pouvez vous connecter.');
        return redirect()->to(site_url('login'));
    }

    public function logout()
    {
        helper('cookie');
        session()->destroy();
        delete_cookie('remember_code');

        return redirect()->to(site_url('login'));
    }
}
