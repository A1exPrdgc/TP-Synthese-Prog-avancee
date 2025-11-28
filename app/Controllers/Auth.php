<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TeachersModel;
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
            'fonction'  => $user['fonction'],
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
            $model = new TeachersModel();
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

        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return view('auth/login', [
                'error' => 'Veuillez remplir tous les champs.',
            ]);
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        $model = new TeachersModel();

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
            setcookie(
                'remember_code',
                $user['code'],
                [
                    'expires'  => time() + (60 * 60 * 24 * 30),
                    'path'     => '/',
                    'domain'   => '',
                    'secure'   => false,
                    'httponly' => true,
                    'samesite' => 'Lax',
                ]
            );
        } else {
            // Supprimer le cookie
            setcookie('remember_code', '', time() - 3600, '/');
        }

        return redirect()->to(site_url('rattrapage'));
    }

    // -------------------------------------------------------------------
    //  PAGE "Mot de passe oublié"
    // -------------------------------------------------------------------
    public function forgotPassword()
    {
        helper('form');

        return view('auth/forgot_password', [
            'error'   => session()->getFlashdata('error'),
            'message' => session()->getFlashdata('message'),
        ]);
    }

    // -------------------------------------------------------------------
    //  TRAITEMENT DU FORMULAIRE "Mot de passe oublié"
    // -------------------------------------------------------------------
    public function doForgotPassword()
    {
        helper('form');

        $login = trim($this->request->getPost('login') ?? '');

        if ($login === '') {
            return view('auth/forgot_password', [
                'error' => 'Veuillez saisir votre identifiant universitaire ou votre email.',
            ]);
        }

        $model = new \App\Models\TeachersModel();

        $user = $model
            ->groupStart()
                ->where('code', $login)
                ->orWhere('email', $login)
            ->groupEnd()
            ->first();

        if (! $user) {
            return view('auth/forgot_password', [
                'message' => 'Si un compte existe pour cet identifiant, un email de réinitialisation a été envoyé.',
            ]);
        }

        $token   = bin2hex(random_bytes(16));
        $expires = Time::now()->addHours(1);

        $model->update($user['code'], [
            'reset_token'   => $token,
            'reset_expires' => $expires->toDateTimeString(),
        ]);

        $emailService = Services::email();

        $resetLink = site_url('reinitialiser/' . $token);

        $emailService->setTo($user['email']);
        $emailService->setSubject('Réinitialisation de votre mot de passe');
        $emailService->setMessage(
            'Bonjour ' . esc($user['prenom']) . ' ' . esc($user['nom']) . ",<br><br>" .
            'Vous avez demandé la réinitialisation de votre mot de passe.<br>' .
            'Cliquez sur le lien ci-dessous (valable 1 heure) :<br><br>' .
            '<a href="' . $resetLink . '">Réinitialiser mon mot de passe</a><br><br>' .
            "Si vous n\'êtes pas à l\'origine de cette demande, ignorez ce message."
        );

        if (! $emailService->send()) {

            return view('auth/forgot_password', [
                'error' => 'Erreur lors de l’envoi de l’email de réinitialisation.',
            ]);
        }

        return view('auth/forgot_password', [
            'message' => 'Un email de réinitialisation a été envoyé (si un compte existe pour cet identifiant).',
        ]);
    }

    // -------------------------------------------------------------------
    //  FORMULAIRE DE NOUVEAU MOT DE PASSE
    // -------------------------------------------------------------------
    public function resetPassword(?string $token = null)
    {
        helper('form');

        if ($token === null) {
            return redirect()->to(site_url('connecter'));
        }

        $model = new \App\Models\TeachersModel();
        $user  = $model->where('reset_token', $token)->first();

        if (! $user) {
            session()->setFlashdata('error', 'Lien de réinitialisation invalide.');
            return redirect()->to(site_url('connecter'));
        }

        if (! empty($user['reset_expires']) && Time::now()->isAfter($user['reset_expires'])) {
            session()->setFlashdata('error', 'Lien de réinitialisation expiré.');
            return redirect()->to(site_url('connecter'));
        }

        return view('auth/reset_password', [
            'token'  => $token,
            'error'  => session()->getFlashdata('error'),
        ]);
    }

    // -------------------------------------------------------------------
    //  TRAITEMENT DU NOUVEAU MOT DE PASSE
    // -------------------------------------------------------------------
    public function doResetPassword(string $token)
    {
        helper('form');

        $rules = [
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('error', 'Mot de passe invalide ou non identique.');
            return redirect()->to(site_url('reinitialiser/' . $token));
        }

        $model = new \App\Models\TeachersModel();
        $user  = $model->where('reset_token', $token)->first();

        if (! $user) {
            session()->setFlashdata('error', 'Lien de réinitialisation invalide.');
            return redirect()->to(site_url('connecter'));
        }

        // Vérifier expiration
        if (! empty($user['reset_expires']) && Time::now()->isAfter($user['reset_expires'])) {
            session()->setFlashdata('error', 'Lien de réinitialisation expiré.');
            return redirect()->to(site_url('connecter'));
        }

        $newPassword = $this->request->getPost('password');

        $model->update($user['code'], [
            'password'      => password_hash($newPassword, PASSWORD_DEFAULT),
            'reset_token'   => null,
            'reset_expires' => null,
        ]);

        session()->setFlashdata('message', 'Mot de passe modifié, vous pouvez vous connecter.');
        return redirect()->to(site_url('connecter'));
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        setcookie('remember_code', '', time() - 3600, '/');
        session()->destroy();

        return redirect()->to('/connecter');
    }

}
