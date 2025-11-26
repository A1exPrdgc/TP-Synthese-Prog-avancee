<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonneModel extends Model
{
    protected $table      = 'absence';
    protected $primaryKey = 'id_ds';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['code', 'absencejustifie'];

    protected $useTimestamps = false;
}