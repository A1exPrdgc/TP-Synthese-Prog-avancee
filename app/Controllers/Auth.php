<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EnseignantModel;
use CodeIgniter\I18n\Time;
use Config\Services;

class Auth extends BaseController
{
    /**
     * Initialise la session pour un enseignant.
     */
    private function startSessionForUser(array $user): void
    {
        $session = session();

        $session->set([
            'connected' => true,
            'code'      => $user['code'],
            'nom'       => $user['nom'],
            'prenom'    => $user['prenom'],
            'email'     => $user['email'],
            'fonction'  => $user['fonction'], // ENS / DE
        ]);
    }

    /**
     * Page de login + auto-login via cookie remember_code
     */
    public function login()
    {
        helper(['form', 'cookie']);

        $session = session();

        // Déjà connecté → accueil rattrapage
        if ($session->get('connected')) {
            return redirect()->to(site_url('rattrapage'));
        }

        // Auto-login via cookie "remember_code"
        $rememberCode = get_cookie('remember_code');
        if ($rememberCode) {
            $model = new EnseignantModel();
            $user  = $model->where('code', $rememberCode)->first();

            if ($user) {
                $this->startSessionForUser($user);
                return redirect()->to(site_url('rattrapage'));
            }
        }

        // Simple affichage du formulaire
        return view('auth/login', [
            'error'   => session()->getFlashdata('error'),
            'message' => session()->getFlashdata('message'),
        ]);
    }

    /**
     * Traitement du formulaire de login
     */
    public function doLogin()
    {
        helper(['form', 'cookie']);

        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(site_url('login'));
        }

        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return view('auth/login', [
                'error' => 'Veuillez remplir tous les champs.',
            ]);
        }

        $username = $this->request->getPost('username'); // code ou email
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        $model = new EnseignantModel();

        // Login par code OU par email
        $user = $model
            ->groupStart()
                ->where('code', $username)
                ->orWhere('email', $username)
            ->groupEnd()
            ->first();

        if (! $user || ! password_verify($password, $user['password'])) {
            return view('auth/login', [
                'error' => 'Identifiant ou mot de passe incorrect.',
            ]);
        }

        // OK → session
        $this->startSessionForUser($user);

        // "Se souvenir de moi"
        if ($remember) {
            set_cookie('remember_code', $user['code'], 60 * 60 * 24 * 30); // 30 jours
        } else {
            delete_cookie('remember_code');
        }

        return redirect()->to(site_url('rattrapage'));
    }

    /**
     * Formulaire de création de compte
     */
    public function signin()
    {
        helper('form');

        return view('auth/signin', [
            'error'   => session()->getFlashdata('error'),
            'message' => session()->getFlashdata('message'),
        ]);
    }

    /**
     * Traitement du formulaire de création de compte
     */
    public function doSignin()
    {
        helper('form');

        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(site_url('signin'));
        }

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

        // Vérifier unicité code / email
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
            'fonction' => 'ENS',
        ];

        // Une seule insert, avec gestion d'erreurs
        if (! $model->insert($data)) {
            dd(
                $model->errors(),
                $model->db->getLastQuery()->getQuery()
            );
        }

        session()->setFlashdata('message', 'Compte créé, vous pouvez vous connecter.');
        return redirect()->to(site_url('login'));
    }


    /**
     * Page "mot de passe oublié"
     */
    public function forgotPassword()
    {
        helper('form');

        return view('auth/forgot_password', [
            'error'   => session()->getFlashdata('error'),
            'message' => session()->getFlashdata('message'),
        ]);
    }

    /**
     * Traitement de la demande de réinitialisation : envoi d’email
     */
    public function doForgotPassword()
    {
        helper('form');

        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(site_url('forgot-password'));
        }

        $login = trim($this->request->getPost('login'));

        if ($login === '') {
            return view('auth/forgot_password', [
                'error' => 'Veuillez saisir votre identifiant ou votre email.',
            ]);
        }

        $model = new EnseignantModel();

        $user = $model
            ->groupStart()
                ->where('code', $login)
                ->orWhere('email', $login)
            ->groupEnd()
            ->first();

        if (! $user) {
            // On ne dit pas si l’utilisateur existe pour éviter de “leaker”
            return view('auth/forgot_password', [
                'message' => 'Si un compte existe pour cet identifiant, un email a été envoyé.',
            ]);
        }

        // Générer un token de reset valable 1h
        $token   = bin2hex(random_bytes(16));
        $expires = Time::now()->addHours(1);

        $model->update($user['code'], [
            'reset_token'   => $token,
            'reset_expires' => $expires->toDateTimeString(),
        ]);

        // Envoi de l’email
        $emailService = Services::email();

        $resetLink = site_url('reset-password/' . $token);

        $emailService->setTo($user['email']);
        $emailService->setSubject('Réinitialisation de votre mot de passe');
        $emailService->setMessage(
            'Bonjour ' . esc($user['prenom']) . ' ' . esc($user['nom']) . ",<br><br>" .
            'Vous avez demandé la réinitialisation de votre mot de passe.<br>' .
            'Cliquez sur le lien ci-dessous (valable 1 heure) :<br><br>' .
            '<a href="' . $resetLink . '">Réinitialiser mon mot de passe</a><br><br>' .
            "Si vous n'êtes pas à l'origine de cette demande, ignorez ce message."
        );

        if (! $emailService->send()) {
            return view('auth/forgot_password', [
                'error' => 'Erreur lors de l\'envoi de l\'email de réinitialisation.',
            ]);
        }

        return view('auth/forgot_password', [
            'message' => 'Un email de réinitialisation a été envoyé (s’il existe un compte pour cet identifiant).',
        ]);
    }

    /**
     * Formulaire de nouveau mot de passe
     */
    public function resetPassword(string $token = null)
    {
        helper('form');

        if ($token === null) {
            return redirect()->to(site_url('login'));
        }

        $model = new EnseignantModel();

        $user = $model->where('reset_token', $token)->first();

        if (! $user) {
            session()->setFlashdata('error', 'Lien de réinitialisation invalide.');
            return redirect()->to(site_url('login'));
        }

        // Vérifier expiration
        if (! empty($user['reset_expires']) && Time::now()->isAfter($user['reset_expires'])) {
            session()->setFlashdata('error', 'Lien de réinitialisation expiré.');
            return redirect()->to(site_url('login'));
        }

        return view('auth/reset_password', [
            'token' => $token,
            'error' => session()->getFlashdata('error'),
        ]);
    }

    /**
     * Traitement du nouveau mot de passe
     */
    public function doResetPassword(string $token)
    {
        helper('form');

        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(site_url('reset-password/' . $token));
        }

        $rules = [
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('error', 'Mot de passe invalide ou non identique.');
            return redirect()->to(site_url('reset-password/' . $token));
        }

        $model = new EnseignantModel();
        $user  = $model->where('reset_token', $token)->first();

        if (! $user) {
            session()->setFlashdata('error', 'Lien de réinitialisation invalide.');
            return redirect()->to(site_url('login'));
        }

        // Vérifier expiration
        if (! empty($user['reset_expires']) && Time::now()->isAfter($user['reset_expires'])) {
            session()->setFlashdata('error', 'Lien de réinitialisation expiré.');
            return redirect()->to(site_url('login'));
        }

        $newPassword = $this->request->getPost('password');

        $model->update($user['code'], [
            'password'      => password_hash($newPassword, PASSWORD_DEFAULT),
            'reset_token'   => null,
            'reset_expires' => null,
        ]);

        session()->setFlashdata('message', 'Mot de passe modifié, vous pouvez vous connecter.');
        return redirect()->to(site_url('login'));
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        helper('cookie');

        session()->destroy();
        delete_cookie('remember_code');

        return redirect()->to(site_url('login'));
    }
}
