<?php
namespace App\Models;
use CodeIgniter\Model;
class DsModel extends Model
{
    protected $table = 'ds';
    protected $primaryKey = 'id_ds';
    protected $allowedFields = ['id_semestre', 'date_ds', 'duree_minutes', 'type_exam', 'coderessource', 'codeenseignant'];

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;
}
