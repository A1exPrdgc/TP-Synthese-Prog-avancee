<?php

namespace App\Models;

use CodeIgniter\Model;

class EnseignantModel extends Model
{
    protected $table            = 'enseignant';
    protected $primaryKey       = 'code';   // PK = code (varchar)
    protected $useAutoIncrement = false;   // très important, sinon CI croit que c'est un int auto

    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'code',
        'nom',
        'prenom',
        'email',
        'password',
        'fonction',
        'reset_token',
        'reset_expires',
    ];
}
