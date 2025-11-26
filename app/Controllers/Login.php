<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        $session = session();

        // Sécurité : si pas connecté → login
        if (!$session->get('user_id')) {
            return redirect()->to('/login');
        }

    }
}