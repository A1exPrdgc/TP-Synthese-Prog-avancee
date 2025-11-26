<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PersonneModel;
use Config\Services;
use Config\Email;

class Auth extends BaseController
{
    /**
     * Affiche le formulaire de login
     * + auto-login via cookie "remember_username" si possible
     */
    public function login()
    {
        $session = session();

        // Si déjà connecté, on redirige vers l'accueil (liste des rattrapages par exemple)
        if ($session->get('user_id')) {
            return redirect()->to('/'); 
        }

        // "Se souvenir de moi" : auto-login via cookie
        $rememberUsername = $this->request->getCookie('remember_username');

        if ($rememberUsername) {
            $userModel = new UserModel();
            $user      = $userModel->findByUsernameWithPersonne($rememberUsername);

            if ($user) {
                $this->connectUser($user);
                return redirect()->to('/'); // connecté automatiquement
            }
        }

        return view('auth/login', [
            'error' => $session->getFlashdata('error'),
        ]);
    }

    /**
     * Traitement du formulaire de login (POST)
     */
    public function doLogin()
    {
        $session = session();

        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember'); // checkbox "se souvenir de moi"

        if ($username === '' || $password === '') {
            $session->setFlashdata('error', 'Veuillez remplir tous les champs.');
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $user      = $userModel->findByUsernameWithPersonne($username);

        if (!$user || !password_verify($password, $user['password'])) {
            $session->setFlashdata('error', 'Identifiants incorrects.');
            return redirect()->to('/login');
        }

        // Connexion
        $this->connectUser($user);

        // Gérer le cookie "se souvenir de moi"
        $this->response->deleteCookie('remember_username');

        if (!empty($remember)) {
            // Cookie valable 30 jours
            $this->response->setCookie(
                'remember_username',
                $user['username'],
                60 * 60 * 24 * 30
            );
        }

        return redirect()->to('/'); // page d'accueil (liste rattrapages)
    }

    /**
     * Page d'inscription (sign in)
     */
    public function signin()
    {
        $session = session();

        return view('auth/signin', [
            'error' => $session->getFlashdata('error'),
        ]);
    }

    /**
     * Traitement du formulaire de sign in
     */
    public function doSignin()
    {
        $session = session();

        $nom      = trim($this->request->getPost('nom'));
        $prenom   = trim($this->request->getPost('prenom'));
        $email    = trim($this->request->getPost('email'));
        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('password_confirm');

        if ($nom === '' || $prenom === '' || $email === '' || $username === '' || $password === '' || $confirm === '') {
            $session->setFlashdata('error', 'Tous les champs sont obligatoires.');
            return redirect()->to('/signin')->withInput();
        }

        if ($password !== $confirm) {
            $session->setFlashdata('error', 'Les mots de passe ne correspondent pas.');
            return redirect()->to('/signin')->withInput();
        }

        $userModel     = new UserModel();
        $personneModel = new PersonneModel();

        // Vérifier unicité username
        if ($userModel->where('username', $username)->first()) {
            $session->setFlashdata('error', 'Cet identifiant est déjà utilisé.');
            return redirect()->to('/signin')->withInput();
        }

        // Création de la personne
        $personneId = $personneModel->insert([
            'nom'    => $nom,
            'prenom' => $prenom,
            'email'  => $email,
        ], true); // true = retourne l'id

        // Création du user
        $userModel->insert([
            'id_personne' => $personneId,
            'username'    => $username,
            'password'    => password_hash($password, PASSWORD_DEFAULT),
            'role'        => 'ENS',
        ]);


        // On log directement l'user après inscription
        $user = $userModel->findByUsernameWithPersonne($username);
        $this->connectUser($user);

        return redirect()->to('/'); // accueil
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        $session = session();

        // On détruit complètement la session
        $session->destroy();

        // On supprime aussi le cookie "remember me"
        $this->response->deleteCookie('remember_username');

        // Redirection propre vers la page de login
        return redirect()->to(site_url('login'));
    }


    /**
     * Connecte un user : crée la session avec les infos nécessaires
     */
    private function connectUser(array $user): void
{
    $session = session();

    $session->set([
        'user_id'  => $user['username'],
        'username' => $user['username'],
        'role'     => $user['role'],
        'nom'      => $user['nom'],
        'prenom'   => $user['prenom'],
        'email'    => $user['email'],
    ]);
}


        /**
     * Formulaire "Mot de passe oublié"
     */
    public function forgotPassword()
    {
        $session = session();

        return view('auth/forgot_password', [
            'error'   => $session->getFlashdata('error'),
            'message' => $session->getFlashdata('message'),
        ]);
    }

    /**
     * Traitement du formulaire "Mot de passe oublié"
     */
    public function sendResetLink()
    {
        $session = session();

        $usernameOrEmail = trim($this->request->getPost('login'));

        if ($usernameOrEmail === '') {
            $session->setFlashdata('error', 'Veuillez saisir votre identifiant ou votre email.');
            return redirect()->to('/forgot-password');
        }

        $userModel = new UserModel();

        // on accepte username OU email
        $user = $userModel
        ->select('users.*, personne.nom AS nom, personne.prenom AS prenom, personne.email')
        ->join('personne', 'personne.id_personne = users.id_personne')
        ->groupStart()
            ->where('users.username', $usernameOrEmail)
            ->orWhere('personne.email', $usernameOrEmail)
        ->groupEnd()
        ->first();


        if (!$user) {
            // on ne précise pas trop pour ne pas "leaker" les comptes
            $session->setFlashdata('message', 'Si un compte existe pour cet identifiant, un lien de réinitialisation a été généré.');
            return redirect()->to('/forgot-password');
        }

        // Génération du token comme déjà fait
        $token   = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', time() + 3600);

        $userModel->update($user['username'], [
            'reset_token'   => $token,
            'reset_expires' => $expires,
        ]);

        $resetLink = site_url('reset-password/' . $token);

        // -------- ENVOI DU MAIL --------
        $email = Services::email();

        $email->setTo($user['email']);
        $email->setFrom('jules.chuzeville@gmail.com', 'Jules');
        $email->setSubject('Réinitialisation de votre mot de passe');
        $email->setMessage(
            "Bonjour {$user['prenom']} {$user['nom']},<br><br>" .
            "Vous avez demandé la réinitialisation de votre mot de passe.<br>" .
            "Cliquez sur le lien ci-dessous (valable 1 heure) :<br><br>" .
            "<a href=\"{$resetLink}\">Réinitialiser mon mot de passe</a><br><br>" .
            "Si vous n'êtes pas à l'origine de cette demande, ignorez ce message."
        );

        if (! $email->send()) {
            // DEBUG TEMPORAIRE
            echo '<pre>';
            print_r($email->printDebugger(['headers', 'subject', 'body']));
            echo '</pre>';
            exit;
        }



        // Message flash pour l'utilisateur
        $session->setFlashdata(
            'message',
            "Un email de réinitialisation a été envoyé si un compte existe pour cet identifiant."
        );

        return redirect()->to('/forgot-password');

    }

    /**
     * Formulaire de réinitialisation (à partir du token)
     */
    public function resetPassword(string $token)
    {
        $session   = session();
        $userModel = new UserModel();
        $user      = $userModel->findByResetToken($token);

        if (!$user) {
            $session->setFlashdata('error', 'Lien de réinitialisation invalide ou expiré.');
            return redirect()->to('/login');
        }

        return view('auth/reset_password', [
            'token' => $token,
            'error' => $session->getFlashdata('error'),
        ]);
    }

    /**
     * Traitement du formulaire de nouveau mot de passe
     */
    public function doResetPassword(string $token)
    {
        $session   = session();
        $userModel = new UserModel();
        $user      = $userModel->findByResetToken($token);

        if (!$user) {
            $session->setFlashdata('error', 'Lien de réinitialisation invalide ou expiré.');
            return redirect()->to('/login');
        }

        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('password_confirm');

        if ($password === '' || $confirm === '') {
            $session->setFlashdata('error', 'Veuillez saisir et confirmer votre nouveau mot de passe.');
            return redirect()->back();
        }

        if ($password !== $confirm) {
            $session->setFlashdata('error', 'Les mots de passe ne correspondent pas.');
            return redirect()->back();
        }

        // Mise à jour du mot de passe + purge du token
        $userModel->update($user['username'], [
            'password'      => password_hash($password, PASSWORD_DEFAULT),
            'reset_token'   => null,
            'reset_expires' => null,
        ]);


        $session->setFlashdata('message', 'Mot de passe réinitialisé, vous pouvez vous connecter.');
        return redirect()->to('/login');
    }

}
