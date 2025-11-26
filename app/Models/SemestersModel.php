<?php
namespace App\Models;
use CodeIgniter\Model;
class SemestersModel extends Model
{
    protected $table = 'semestre';
    protected $primaryKey = 'id_semestre';
    protected $allowedFields = ['code', 'annee'];

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;

    public function getAllSemesters()
    {
        //return ["S1", "S2"];
        return $this->select('code')->findAll();
    }
}