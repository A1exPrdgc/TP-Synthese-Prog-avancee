<?php

namespace App\Controllers;

use App\Models\RattrapageModel;

class Rattrapage extends BaseController
{
    public function index()
    {
        $session = session();

        // Sécurité : si pas connecté → login
        if (!$session->get('user_id')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role'); // DE / ENS / etc.

        $model = new RattrapageModel();
        $rattrapages = $model->getAllWithDetails();

        return view('rattrapage/index', [
            'rattrapages' => $rattrapages,
            'role'        => $role,
        ]);
    }
}
