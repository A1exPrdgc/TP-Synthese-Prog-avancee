<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PersonneModel;

use Config\Services;
use Config\Email;

class Debug extends BaseController{
    public function testMailConfig()
    {
        $emailConfig = config('Email');     // instance de Config\Email
        dd($emailConfig);
    }
}

