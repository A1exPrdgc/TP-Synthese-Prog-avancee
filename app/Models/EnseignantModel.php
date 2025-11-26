<?php

namespace App\Models;

use CodeIgniter\Model;

class EnseignantModel extends Model
{
    protected $table            = 'enseignant';
    protected $primaryKey       = 'code';
    protected $useAutoIncrement = false;

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
