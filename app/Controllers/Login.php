<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        $session = session();

        // Sécurité : si pas connecté → login
        if (!$session->get('connected')) {
            return redirect()->to('/login');
        }

    }
}