<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonneModel extends Model
{
    protected $table      = 'personne';
    protected $primaryKey = 'id_personne';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nom', 'prenom', 'email'];

    protected $useTimestamps = false;
}
