<?php
namespace App\Models;
use CodeIgniter\Model;
class RessourceModel extends Model
{
    protected $table = 'ressource';
    protected $primaryKey = 'coderessource';
    protected $allowedFields = ['nomressource'];

    
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;

    public function getAllResourcesBySemesters($semesterId)
    {
        //return ["R1.05 blabla", "R1.02 blibli"];
        return $this->select('ressource.coderessource, ressource.nomressource')
                    ->join('ds d', 'd.coderessource = ressource.coderessource')
                    ->join('semestre s', 's.id_semestre = d.id_semestre')
                    ->where('s.code', $semesterId)
                    ->findAll();
    }
}
